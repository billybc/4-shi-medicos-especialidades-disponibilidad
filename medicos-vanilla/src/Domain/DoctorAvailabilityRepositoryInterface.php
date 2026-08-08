<?php

declare(strict_types=1);

namespace Medicos\Domain;

interface DoctorAvailabilityRepositoryInterface
{
    /** @return array<int, Doctor> */
    public function allDoctors(): array;

    public function findDoctor(int $doctorId): ?Doctor;

    /** @return array<int, AvailabilitySlot> */
    public function schedule(?int $doctorId = null): array;

    public function countDoctors(): int;

    public function countSlots(): int;

    public function countTodaySlots(): int;

    public function hasOverlap(int $doctorId, string $availabilityDate, string $startTime, string $endTime): bool;

    public function saveSlot(
        int $doctorId,
        string $availabilityDate,
        string $startTime,
        string $endTime,
        string $status,
        ?string $note
    ): int;
}
