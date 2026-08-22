<?php
declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';

use Medicos\Domain\AvailabilityService;
use Medicos\Domain\DoctorAvailabilityRepositoryInterface;
use Medicos\Domain\Doctor;

class DoubleRepository implements DoctorAvailabilityRepositoryInterface {
    public bool $simularFalloPDO = false;

    public function allDoctors(): array { return []; }
    public function findDoctor(int $id): ?Doctor { return new Doctor($id, 'Dr. Prueba', 'Pediatría', 'MED-100', true); }
    public function schedule(?int $doctorId = null): array { return []; }
    public function countDoctors(): int { return 1; }
    public function countSlots(): int { return 0; }
    public function countTodaySlots(): int { return 0; }
    public function hasOverlap(int $d, string $f, string $i, string $f2): bool { return false; }
    
    public function saveDoctor(string $fullName, string $specialty, string $licenseNumber): int {
        if ($this->simularFalloPDO) { throw new \RuntimeException('Error al guardar médico en la base de datos'); }
        return 1;
    }

    public function saveSlot(int $d, string $f, string $i, string $f2, string $s, ?string $n): int {
        if ($this->simularFalloPDO) { throw new \RuntimeException('Error de conexión a la base de datos'); }
        return 99;
    }
}

$repo = new DoubleRepository();
$service = new AvailabilityService($repo);

// 1. CAMINO FELIZ
$ok = $service->registerAvailability([
    'doctor_id' => 1, 
    'availability_date' => '2026-09-10', 
    'start_time' => '08:00', 
    'end_time' => '10:00', 
    'status' => 'disponible'
]);
assert($ok['id'] === 99, 'Prueba 1 Fallada');
echo "✔ Camino feliz: Correcto\n";

// 2. REGLA DE DOMINIO (Hora final anterior a la inicial)
try {
    $service->registerAvailability([
        'doctor_id' => 1, 
        'availability_date' => '2026-09-10', 
        'start_time' => '11:00', 
        'end_time' => '09:00', 
        'status' => 'disponible'
    ]);
    echo "❌ Regla de dominio falló\n";
} catch (\InvalidArgumentException $e) {
    echo "✔ Regla de dominio (Validación de hora): Correcto\n";
}

// 3. ERROR DE PERSISTENCIA
try {
    $repo->simularFalloPDO = true;
    $service->registerAvailability([
        'doctor_id' => 1, 
        'availability_date' => '2026-09-10', 
        'start_time' => '08:00', 
        'end_time' => '10:00', 
        'status' => 'disponible'
    ]);
} catch (\RuntimeException $e) {
    echo "✔ Error de persistencia capturado: Correcto\n";
}