<?php

declare(strict_types=1);

namespace Medicos\Domain;

final class Doctor
{
    public function __construct(
        public readonly int $id,
        public readonly string $fullName,
        public readonly string $specialty,
        public readonly string $licenseNumber,
        public readonly bool $active
    ) {
    }
}
