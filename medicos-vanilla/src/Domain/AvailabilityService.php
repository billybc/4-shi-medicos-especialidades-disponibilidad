<?php

declare(strict_types=1);

namespace Medicos\Domain;

use InvalidArgumentException;
use RuntimeException;

final class AvailabilityService
{
    public function __construct(private DoctorAvailabilityRepositoryInterface $repository)
    {
    }

    public function dashboard(?int $doctorId = null): array
    {
        $doctors = $this->repository->allDoctors();

        if ($doctorId === null && $doctors !== []) {
            $doctorId = $doctors[0]->id;
        }

        $selectedDoctor = $doctorId === null ? null : $this->repository->findDoctor($doctorId);

        if ($doctorId !== null && $selectedDoctor === null) {
            throw new RuntimeException('No se encontro el medico solicitado.');
        }

        return [
            'doctors' => $doctors,
            'selectedDoctor' => $selectedDoctor,
            'schedule' => $this->repository->schedule($doctorId),
            'metrics' => [
                'doctors' => $this->repository->countDoctors(),
                'slots' => $this->repository->countSlots(),
                'todaySlots' => $this->repository->countTodaySlots(),
            ],
        ];
    }

    public function registerAvailability(array $input): array
    {
        $doctorId = filter_var($input['doctor_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $availabilityDate = trim((string) ($input['availability_date'] ?? ''));
        $startTime = $this->normalizeTime((string) ($input['start_time'] ?? ''));
        $endTime = $this->normalizeTime((string) ($input['end_time'] ?? ''));
        $status = trim((string) ($input['status'] ?? 'disponible'));
        $note = trim((string) ($input['note'] ?? ''));

        if ($doctorId === false) {
            throw new InvalidArgumentException('Selecciona un medico valido.');
        }

        if ($this->repository->findDoctor((int) $doctorId) === null) {
            throw new InvalidArgumentException('El medico seleccionado no existe.');
        }

        if ($availabilityDate === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $availabilityDate)) {
            throw new InvalidArgumentException('Ingresa una fecha valida con formato YYYY-MM-DD.');
        }

        if ($startTime === '' || $endTime === '') {
            throw new InvalidArgumentException('Completa la hora inicial y la hora final.');
        }

        if ($endTime <= $startTime) {
            throw new InvalidArgumentException('La hora final debe ser posterior a la inicial.');
        }

        if (!in_array($status, ['disponible', 'ocupado', 'bloqueado'], true)) {
            throw new InvalidArgumentException('El estado de disponibilidad no es valido.');
        }

        if ($note !== '' && strlen($note) > 255) {
            throw new InvalidArgumentException('La observacion no puede superar 255 caracteres.');
        }

        if ($this->repository->hasOverlap((int) $doctorId, $availabilityDate, $startTime, $endTime)) {
            throw new InvalidArgumentException('Ya existe un bloque que se cruza con ese horario.');
        }

        $slotId = $this->repository->saveSlot(
            (int) $doctorId,
            $availabilityDate,
            $startTime,
            $endTime,
            $status,
            $note === '' ? null : $note,
        );

        return [
            'id' => $slotId,
            'message' => 'Disponibilidad registrada correctamente.',
        ];
    }

    private function normalizeTime(string $value): string
    {
        $value = trim($value);

        if (!preg_match('/^\d{2}:\d{2}$/', $value)) {
            return '';
        }

        return $value;
    }
}
