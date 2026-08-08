<?php

declare(strict_types=1);

return [
    'doctors' => [
        ['full_name' => 'Dra. Andrea Lopez', 'specialty' => 'Medicina Interna', 'license_number' => 'MED-2026-0001', 'active' => true],
        ['full_name' => 'Dr. Carlos Mejia', 'specialty' => 'Cardiologia', 'license_number' => 'MED-2026-0002', 'active' => true],
        ['full_name' => 'Dra. Lucia Ramirez', 'specialty' => 'Pediatria', 'license_number' => 'MED-2026-0003', 'active' => true],
        ['full_name' => 'Dr. Mateo Perez', 'specialty' => 'Traumatologia', 'license_number' => 'MED-2026-0004', 'active' => true],
    ],
    'availability_slots' => [
        ['doctor_id' => 1, 'availability_date' => '2026-08-08', 'start_time' => '08:00', 'end_time' => '12:00', 'status' => 'disponible', 'note' => 'Consulta ambulatoria general.'],
        ['doctor_id' => 1, 'availability_date' => '2026-08-08', 'start_time' => '13:00', 'end_time' => '16:00', 'status' => 'disponible', 'note' => 'Seguimiento de pacientes.'],
        ['doctor_id' => 2, 'availability_date' => '2026-08-08', 'start_time' => '09:00', 'end_time' => '11:30', 'status' => 'disponible', 'note' => 'Revision de especialidad.'],
        ['doctor_id' => 3, 'availability_date' => '2026-08-09', 'start_time' => '08:30', 'end_time' => '12:30', 'status' => 'disponible', 'note' => 'Consulta pediatrica de control.'],
    ],
];
