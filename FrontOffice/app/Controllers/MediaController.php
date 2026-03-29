<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class MediaController extends Controller
{
    private const PRESETS = [
        'logo' => ['width' => 52, 'height' => 52],
        'logo2x' => ['width' => 104, 'height' => 104],
        'thumb' => ['width' => 420, 'height' => 260],
        'thumb2x' => ['width' => 840, 'height' => 520],
        'hero' => ['width' => 960, 'height' => 540],
        'hero2x' => ['width' => 1280, 'height' => 720],
        'cover' => ['width' => 1200, 'height' => 675],
        'cover2x' => ['width' => 1600, 'height' => 900],
        'gallery' => ['width' => 640, 'height' => 480],
        'gallery2x' => ['width' => 960, 'height' => 720],
    ];

    public function resize(string $preset = '', string ...$pathSegments): void
    {
        if ($preset === '' || !isset(self::PRESETS[$preset]) || $pathSegments === []) {
            $this->notFound();
            return;
        }

        $relativePath = implode('/', array_map(static fn (string $value): string => rawurldecode($value), $pathSegments));
        $relativePath = ltrim($relativePath, '/');

        if ($relativePath === '' || str_contains($relativePath, '..')) {
            $this->notFound();
            return;
        }

        $isAllowedPrefix = str_starts_with($relativePath, 'uploads/') || str_starts_with($relativePath, 'assets/images/');
        if (!$isAllowedPrefix) {
            $this->notFound();
            return;
        }

        $publicRoot = dirname(__DIR__, 2);
        $sourcePath = $publicRoot . '/' . $relativePath;

        if (!is_file($sourcePath)) {
            $this->notFound();
            return;
        }

        $size = @getimagesize($sourcePath);
        if ($size === false) {
            $this->notFound();
            return;
        }

        [$sourceWidth, $sourceHeight, $type] = $size;
        if ($sourceWidth <= 0 || $sourceHeight <= 0) {
            $this->notFound();
            return;
        }

        if (!in_array($type, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            $this->notFound();
            return;
        }

        $presetConfig = self::PRESETS[$preset];
        $cacheDir = $publicRoot . '/uploads/.cache';

        if (!is_dir($cacheDir) && !@mkdir($cacheDir, 0775, true) && !is_dir($cacheDir)) {
            $this->notFound();
            return;
        }

        $mtime = (int) filemtime($sourcePath);
        $cacheKey = sha1($preset . '|' . $relativePath . '|' . $mtime);
        $cachePath = $cacheDir . '/' . $preset . '-' . $cacheKey . '.webp';

        if (!is_file($cachePath)) {
            $result = $this->createResizedWebp(
                $sourcePath,
                $cachePath,
                $type,
                $sourceWidth,
                $sourceHeight,
                (int) $presetConfig['width'],
                (int) $presetConfig['height']
            );

            if (!$result || !is_file($cachePath)) {
                $this->notFound();
                return;
            }
        }

        header('Content-Type: image/webp');
        header('Content-Length: ' . (string) filesize($cachePath));
        header('Cache-Control: public, max-age=2592000, immutable');
        header('X-Image-Variant: ' . $preset);
        readfile($cachePath);
    }

    private function createResizedWebp(
        string $sourcePath,
        string $targetPath,
        int $sourceType,
        int $sourceWidth,
        int $sourceHeight,
        int $targetWidth,
        int $targetHeight
    ): bool {
        $sourceImage = match ($sourceType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($sourcePath),
            default => false,
        };

        if ($sourceImage === false) {
            return false;
        }

        $scale = max($targetWidth / $sourceWidth, $targetHeight / $sourceHeight);

        if ($scale >= 1.0) {
            $dstWidth = $sourceWidth;
            $dstHeight = $sourceHeight;
            $srcX = 0;
            $srcY = 0;
            $srcW = $sourceWidth;
            $srcH = $sourceHeight;
        } else {
            $cropW = (int) round($targetWidth / $scale);
            $cropH = (int) round($targetHeight / $scale);
            $cropW = min($cropW, $sourceWidth);
            $cropH = min($cropH, $sourceHeight);

            $srcX = (int) floor(($sourceWidth - $cropW) / 2);
            $srcY = (int) floor(($sourceHeight - $cropH) / 2);
            $srcW = $cropW;
            $srcH = $cropH;
            $dstWidth = $targetWidth;
            $dstHeight = $targetHeight;
        }

        $targetImage = imagecreatetruecolor($dstWidth, $dstHeight);
        if ($targetImage === false) {
            imagedestroy($sourceImage);
            return false;
        }

        imagealphablending($targetImage, true);
        imagesavealpha($targetImage, true);

        $ok = imagecopyresampled(
            $targetImage,
            $sourceImage,
            0,
            0,
            $srcX,
            $srcY,
            $dstWidth,
            $dstHeight,
            $srcW,
            $srcH
        );

        if (!$ok) {
            imagedestroy($targetImage);
            imagedestroy($sourceImage);
            return false;
        }

        $written = imagewebp($targetImage, $targetPath, 82);

        imagedestroy($targetImage);
        imagedestroy($sourceImage);

        return $written;
    }

    private function notFound(): void
    {
        http_response_code(404);
        header('Content-Type: text/plain; charset=UTF-8');
        echo 'Image not found';
    }
}
