<?php

declare(strict_types=1);

namespace App\Models;

final class UserModel extends Model
{
    public int $id_utilisateur;
    public string $nom;
    public string $email;
    public int $id_type_utilisateur;
    public ?string $mot_de_passe = null;
}
