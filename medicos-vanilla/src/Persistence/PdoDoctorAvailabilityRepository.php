<?php

declare(strict_types=1);

namespace Medicos\Persistence;

use Medicos\Domain\AvailabilitySlot;
use Medicos\Domain\Doctor;
use Medicos\Domain\DoctorAvailabilityRepositoryInterface;
use PDO;

final class PdoDoctorAvailabilityRepository implements DoctorAvailabilityRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }
    public function saveDoctor(string $fullName, string $specialty, string $licenseNumber): int
{
        $stmt = $this->pdo->prepare(
        'INSERT INTO doctors (full_name, specialty, license_number) VALUES (:full_name, :specialty, :license_number)'
     );
        $stmt->execute([
        ':full_name' => $fullName,
        ':specialty' => $specialty,
        ':license_number' => $licenseNumber,
    ]);

    return (int) $this->pdo->lastInsertId();
}

    public function allDoctors(): array
    {
        $statement = $this->pdo->query('SELECT id, full_name, specialty, license_number, active FROM doctors ORDER BY full_name');
        $doctors = [];

        foreach ($statement->fetchAll() as $row) {
            $doctors[] = $this->mapDoctor($row);
        }

        return $doctors;
    }

    public function findDoctor(int $doctorId): ?Doctor
    {
        $statement = $this->pdo->prepare('SELECT id, full_name, specialty, license_number, active FROM doctors WHERE id = :id');
        $statement->execute([':id' => $doctorId]);
        $row = $statement->fetch();

        return $row === false ? null : $this->mapDoctor($row);
    }

    public function schedule(?int $doctorId = null): array
    {
        $sql = 'SELECT s.id, s.doctor_id, s.availability_date, s.start_time, s.end_time, s.status, s.note
                FROM availability_slots s
                ' . ($doctorId === null ? '' : 'WHERE s.doctor_id = :doctor_id ') .
                'ORDER BY s.availability_date DESC, s.start_time ASC';

        $statement = $this->pdo->prepare($sql);

        if ($doctorId !== null) {
            $statement->execute([':doctor_id' => $doctorId]);
        } else {
            $statement->execute();
        }

        $slots = [];

        foreach ($statement->fetchAll() as $row) {
            $slots[] = $this->mapSlot($row);
        }

        return $slots;
    }

    public function countDoctors(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM doctors')->fetchColumn();
    }

    public function countSlots(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM availability_slots')->fetchColumn();
    }

    public function countTodaySlots(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM availability_slots WHERE availability_date = date('now')")->fetchColumn();
    }

    public function hasOverlap(int $doctorId, string $availabilityDate, string $startTime, string $endTime): bool
    {
        $statement = $this->pdo->prepare(
            'SELECT 1
             FROM availability_slots
             WHERE doctor_id = :doctor_id
               AND availability_date = :availability_date
               AND status <> "bloqueado"
               AND NOT (end_time <= :start_time OR start_time >= :end_time)
             LIMIT 1'
        );

        $statement->execute([
            ':doctor_id' => $doctorId,
            ':availability_date' => $availabilityDate,
            ':start_time' => $startTime,
            ':end_time' => $endTime,
        ]);

        return $statement->fetchColumn() !== false;
    }

    public function saveSlot(int $doctorId, string $availabilityDate, string $startTime, string $endTime, string $status, ?string $note): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO availability_slots (doctor_id, availability_date, start_time, end_time, status, note, created_at, updated_at)
             VALUES (:doctor_id, :availability_date, :start_time, :end_time, :status, :note, datetime("now"), datetime("now"))'
        );

        $statement->execute([
            ':doctor_id' => $doctorId,
            ':availability_date' => $availabilityDate,
            ':start_time' => $startTime,
            ':end_time' => $endTime,
            ':status' => $status,
            ':note' => $note,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    private function mapDoctor(array $row): Doctor
    {
        return new Doctor(
            (int) $row['id'],
            (string) $row['full_name'],
            (string) $row['specialty'],
            (string) $row['license_number'],
            (bool) $row['active'],
        );
    }

    private function mapSlot(array $row): AvailabilitySlot
    {
        return new AvailabilitySlot(
            (int) $row['id'],
            (int) $row['doctor_id'],
            (string) $row['availability_date'],
            (string) $row['start_time'],
            (string) $row['end_time'],
            (string) $row['status'],
            $row['note'] !== null ? (string) $row['note'] : null,
        );
    }
}
