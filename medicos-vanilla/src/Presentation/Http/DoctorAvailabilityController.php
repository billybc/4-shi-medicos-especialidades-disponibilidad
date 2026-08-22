<?php

declare(strict_types=1);

namespace Medicos\Presentation\Http;

use Medicos\Domain\AvailabilityService;
use InvalidArgumentException;
use RuntimeException;

final class DoctorAvailabilityController
{
    public function __construct(private AvailabilityService $service)
    {
    }

    public function handle(array $server, array $get, array $post): array
    {
        $flash = null;

        if (($server['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            try {
                $this->service->registerAvailability($post);
                $flash = [
                    'type' => 'success',
                    'message' => 'Disponibilidad registrada correctamente.',
                ];
            } catch (InvalidArgumentException|RuntimeException $exception) {
                $flash = [
                    'type' => 'error',
                    'message' => $exception->getMessage(),
                ];
            }
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'register_doctor') {
        $this->service->registerDoctor([
        'full_name' => $_POST['full_name'] ?? '',
        'specialty' => $_POST['specialty'] ?? '',
        'license_number' => $_POST['license_number'] ?? '',
        ]);
        header('Location: /');
        exit;
}

        $doctorId = isset($get['doctor_id']) && $get['doctor_id'] !== '' ? (int) $get['doctor_id'] : null;

        return [
            'flash' => $flash,
            'dashboard' => $this->service->dashboard($doctorId),
            'formDoctorId' => $doctorId,
        ];
    }
    
}
