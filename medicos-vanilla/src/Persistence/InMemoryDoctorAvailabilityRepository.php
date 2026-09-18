<?php

declare(strict_types=1);

namespace Medicos\Persistence;

use Medicos\Domain\AvailabilitySlot;
use Medicos\Domain\Doctor;
use Medicos\Domain\DoctorAvailabilityRepositoryInterface;

/**
 * Adaptador en memoria de DoctorAvailabilityRepositoryInterface.
 * Útil para pruebas unitarias rápidas sin depender de SQLite/PDO
 * (camino feliz, reglas de dominio) y para dobles de prueba de error de persistencia.
 */
final class InMemoryDoctorAvailabilityRepository implements DoctorAvailabilityRepositoryInterface
{
    /** @var array<int, Doctor> */
    private array $doctors = [];

    /** @var array<int, AvailabilitySlot> */
    private array $slots = [];

    private int $nextDoctorId = 1;
    private int $nextSlotId = 1;

    /** @return array<int, Doctor> */
    public function allDoctors(): array
    {
        return array_values($this->doctors);
    }

    public function findDoctor(int $doctorId): ?Doctor
    {
        return $this->doctors[$doctorId] ?? null;
    }

    /** @return array<int, AvailabilitySlot> */
    public function schedule(?int $doctorId = null): array
    {
        if ($doctorId === null) {
            return array_values($this->slots);
        }

        return array_values(array_filter(
            $this->slots,
            fn (AvailabilitySlot $slot): bool => $slot->doctorId === $doctorId
        ));
    }

    public function countDoctors(): int
    {
        return count($this->doctors);
    }

    public function countSlots(): int
    {
        return count($this->slots);
    }

    public function countTodaySlots(): int
    {
        $today = date('Y-m-d');

        return count(array_filter(
            $this->slots,
            fn (AvailabilitySlot $slot): bool => $slot->availabilityDate === $today
        ));
    }

    public function hasOverlap(int $doctorId, string $availabilityDate, string $startTime, string $endTime): bool
    {
        foreach ($this->slots as $slot) {
            if ($slot->doctorId !== $doctorId || $slot->availabilityDate !== $availabilityDate) {
                continue;
            }

            if ($startTime < $slot->endTime && $slot->startTime < $endTime) {
                return true;
            }
        }

        return false;
    }

    public function saveDoctor(string $fullName, string $specialty, string $licenseNumber): int
    {
        $id = $this->nextDoctorId++;

        $this->doctors[$id] = new Doctor(
            id: $id,
            fullName: $fullName,
            specialty: $specialty,
            licenseNumber: $licenseNumber,
            active: true
        );

        return $id;
    }

    public function saveSlot(
        int $doctorId,
        string $availabilityDate,
        string $startTime,
        string $endTime,
        string $status,
        ?string $note
    ): int {
        $id = $this->nextSlotId++;

        $this->slots[$id] = new AvailabilitySlot(
            id: $id,
            doctorId: $doctorId,
            availabilityDate: $availabilityDate,
            startTime: $startTime,
            endTime: $endTime,
            status: $status,
            note: $note
        );

        return $id;
    }
}