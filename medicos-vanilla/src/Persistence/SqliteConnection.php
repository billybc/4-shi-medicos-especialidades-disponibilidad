<?php

declare(strict_types=1);

namespace Medicos\Persistence;

use PDO;
use RuntimeException;

final class SqliteConnection
{
    public static function make(): PDO
    {
        self::ensureStorageDirectory();

        $pdo = new PDO('sqlite:' . MEDICOS_DATABASE_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec('PRAGMA foreign_keys = ON');

        self::initializeIfNeeded($pdo);

        return $pdo;
    }

    private static function ensureStorageDirectory(): void
    {
        if (!is_dir(MEDICOS_STORAGE_PATH) && !mkdir(MEDICOS_STORAGE_PATH, 0777, true) && !is_dir(MEDICOS_STORAGE_PATH)) {
            throw new RuntimeException('No se pudo crear la carpeta storage.');
        }
    }

    private static function initializeIfNeeded(PDO $pdo): void
    {
        $statement = $pdo->query("SELECT name FROM sqlite_master WHERE type = 'table' AND name = 'doctors'");

        if ($statement->fetchColumn() !== false) {
            return;
        }

        $schema = file_get_contents(MEDICOS_SCHEMA_PATH);

        if ($schema === false) {
            throw new RuntimeException('No se pudo leer el esquema SQLite.');
        }

        $pdo->exec($schema);

        /** @var array{doctors: array<int, array<string, mixed>>, availability_slots: array<int, array<string, mixed>>} $seed */
        $seed = require MEDICOS_SEED_PATH;

        foreach ($seed['doctors'] as $doctor) {
            $pdo->prepare(
                'INSERT INTO doctors (full_name, specialty, license_number, active, created_at, updated_at)
                 VALUES (:full_name, :specialty, :license_number, :active, datetime("now"), datetime("now"))'
            )->execute([
                ':full_name' => $doctor['full_name'],
                ':specialty' => $doctor['specialty'],
                ':license_number' => $doctor['license_number'],
                ':active' => $doctor['active'] ? 1 : 0,
            ]);
        }

        foreach ($seed['availability_slots'] as $slot) {
            $pdo->prepare(
                'INSERT INTO availability_slots (doctor_id, availability_date, start_time, end_time, status, note, created_at, updated_at)
                 VALUES (:doctor_id, :availability_date, :start_time, :end_time, :status, :note, datetime("now"), datetime("now"))'
            )->execute([
                ':doctor_id' => $slot['doctor_id'],
                ':availability_date' => $slot['availability_date'],
                ':start_time' => $slot['start_time'],
                ':end_time' => $slot['end_time'],
                ':status' => $slot['status'],
                ':note' => $slot['note'],
            ]);
        }
    }
}
