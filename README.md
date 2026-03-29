# Mini-projet-Iran

Ce repository contient deux applications PHP sans framework (architecture MVC classique) :
- `FrontOffice` (site public)
- `BackOffice` (administration)

La base de donnees PostgreSQL est initialisee automatiquement avec le script `db/001_init.sql`.

## Prerequis

- Docker Desktop
- Docker Compose (inclus dans Docker Desktop)

## Demarrage rapide

1. Copier le fichier d'environnement :
   - Windows PowerShell : `Copy-Item .env.example .env`
   - Linux/macOS : `cp .env.example .env`
2. Demarrer les services :
   - `docker compose up -d`
3. Ouvrir :
   - FrontOffice : http://localhost:8080
   - BackOffice : http://localhost:8081

## Services Docker

- `frontoffice` : PHP + Apache (`php:8.3-apache`)
- `backoffice` : PHP + Apache (`php:8.3-apache`)
- `db` : PostgreSQL 15 (`postgres:15`)

## Connexion base de donnees

- Host: `db`
- Port: `5432`
- Database: valeur de `POSTGRES_DB`
- User: valeur de `POSTGRES_USER`
- Password: valeur de `POSTGRES_PASSWORD`

## Mise sur GitHub

Fichiers recommandes pour le commit initial :
- `docker-compose.yml`
- `.env.example`
- `.gitignore`
- `README.md`
- `db/001_init.sql`
- `FrontOffice/.env.example`
- `BackOffice/.env.example`

Ne pas commiter le fichier `.env` (ignore via `.gitignore`).
