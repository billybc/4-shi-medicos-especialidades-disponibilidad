# Medicos Vanilla

Variante independiente del parcial para el modulo de medicos, especialidades y disponibilidad.

## Que incluye

- PHP 8.2 vanilla, sin framework.
- SQLite con PDO.
- Capas separadas: Presentation, Domain y Persistence.
- UI local visible por navegador.
- Datos ficticios sin informacion clinica identificable.

## Estructura

- `public/` entrada web.
- `src/Domain/` reglas y contratos.
- `src/Persistence/` acceso a SQLite con PDO.
- `src/Presentation/` controlador HTTP.
- `database/schema.sql` esquema editable.
- `database/seed.php` datos demo ficticios.

## Como correrlo

1. Verifica PHP 8.2 con `php -v`.
2. Desde la raiz del worktree ejecuta:
   `php -S 127.0.0.1:8000 -t medicos-vanilla/public`
3. Abre `http://127.0.0.1:8000`.

La base SQLite se crea sola en `medicos-vanilla/storage/medicos.sqlite` cuando no existe.

## Regla central

La validacion de cruces de horarios vive en la capa de dominio, no en el controlador ni en SQL embebido en la vista.
