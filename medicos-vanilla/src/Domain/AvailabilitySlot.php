<?php

declare(strict_types=1);

namespace Medicos\Domain;

final class AvailabilitySlot
{
    public function __construct(
        public readonly int $id,
        public readonly int $doctorId,
        public readonly string $availabilityDate,
        public readonly string $startTime,
        public readonly string $endTime,
        public readonly string $status,
        public readonly ?string $note
    ) {
    }
}
