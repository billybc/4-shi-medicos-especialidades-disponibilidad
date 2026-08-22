<?php

declare(strict_types=1);

use Medicos\Domain\AvailabilityService;
use Medicos\Persistence\PdoDoctorAvailabilityRepository;
use Medicos\Persistence\SqliteConnection;
use Medicos\Presentation\Http\DoctorAvailabilityController;

require __DIR__ . '/../bootstrap.php';

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$pdo = SqliteConnection::make();
$repository = new PdoDoctorAvailabilityRepository($pdo);
$service = new AvailabilityService($repository);
$controller = new DoctorAvailabilityController($service);
$view = $controller->handle($_SERVER, $_GET, $_POST);

$flash = $view['flash'];
$dashboard = $view['dashboard'];
$doctors = $dashboard['doctors'];
$selectedDoctor = $dashboard['selectedDoctor'];
$schedule = $dashboard['schedule'];
$metrics = $dashboard['metrics'];
$selectedDoctorId = $selectedDoctor?->id ?? '';

?><!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Medicos Vanilla | Disponibilidad</title>
    <link rel="stylesheet" href="assets/app.css">
</head>
<body>
<main class="shell">
    <section class="hero">
        <div>
            <p class="eyebrow">ASII - Variante medicos</p>
            <h1>Disponibilidad de medicos con PHP 8.2 vanilla y SQLite</h1>
            <p class="lede">Aplicacion sin framework, con capas separadas y datos ficticios para demostrar el flujo completo desde el navegador hasta la persistencia local.</p>
        </div>
        <div class="hero-card">
            <span>Estado local</span>
            <strong>Base SQLite auto-inicializada</strong>
            <small>Archivo: storage/medicos.sqlite</small>
        </div>
    </section>

    <section class="metrics">
        <article>
            <span>Medicos</span>
            <strong><?= e((string) $metrics['doctors']) ?></strong>
        </article>
        <article>
            <span>Bloques guardados</span>
            <strong><?= e((string) $metrics['slots']) ?></strong>
        </article>
        <article>
            <span>Bloques para hoy</span>
            <strong><?= e((string) $metrics['todaySlots']) ?></strong>
        </article>
    </section>

    <?php if ($flash !== null): ?>
        <section class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></section>
    <?php endif; ?>

    <section class="grid">
        <article class="panel">
            <h2>Registrar disponibilidad</h2>


            <!-- Formulario de Registro de Médico -->
        <form action="/?action=register_doctor" method="POST" class="card">
            <h3>Registrar Nuevo Médico</h3>
    
            <label>Nombre Completo</label>
            <input type="text" name="full_name" required placeholder="Ej. Dr. Juan Pérez">

            <label>Especialidad</label>
            <input type="text" name="specialty" required placeholder="Ej. Pediatría">

            <label>No. Licencia / Colegiado</label>
            <input type="text" name="license_number" required placeholder="Ej. MED-2026">

                    <button type="submit">Guardar Médico</button>
        </form>


            <form method="post">
                <label>
                    Medico
                    <select name="doctor_id" required>
                        <option value="">Selecciona uno</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?= e((string) $doctor->id) ?>" <?= (string) $doctor->id === (string) $selectedDoctorId ? 'selected' : '' ?>>
                                <?= e($doctor->fullName) ?> - <?= e($doctor->specialty) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <div class="two-cols">
                    <label>
                        Fecha
                        <input type="date" name="availability_date" required>
                    </label>
                    <label>
                        Estado
                        <select name="status" required>
                            <option value="disponible">Disponible</option>
                            <option value="ocupado">Ocupado</option>
                            <option value="bloqueado">Bloqueado</option>
                        </select>
                    </label>
                </div>

                <div class="two-cols">
                    <label>
                        Inicio
                        <input type="time" name="start_time" required>
                    </label>
                    <label>
                        Fin
                        <input type="time" name="end_time" required>
                    </label>
                </div>

                <label>
                    Observacion breve
                    <textarea name="note" rows="3" maxlength="255" placeholder="Ej: consulta externa sin datos identificables"></textarea>
                </label>

                <button type="submit">Guardar bloque</button>
            </form>
        </article>

        <article class="panel">
            <h2>Horario visible</h2>
            <form method="get" class="filter-row">
                <label>
                    Ver medico
                    <select name="doctor_id" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?= e((string) $doctor->id) ?>" <?= (string) $doctor->id === (string) $selectedDoctorId ? 'selected' : '' ?>>
                                <?= e($doctor->fullName) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </form>

            <?php if ($selectedDoctor !== null): ?>
                <div class="doctor-card">
                    <strong><?= e($selectedDoctor->fullName) ?></strong>
                    <span><?= e($selectedDoctor->specialty) ?></span>
                    <small>Licencia <?= e($selectedDoctor->licenseNumber) ?></small>
                </div>
            <?php endif; ?>

            <div class="schedule-list">
                <?php foreach ($schedule as $slot): ?>
                    <div class="slot">
                        <div>
                            <strong><?= e($slot->availabilityDate) ?></strong>
                            <p><?= e($slot->startTime) ?> - <?= e($slot->endTime) ?></p>
                        </div>
                        <div class="slot-badge slot-<?= e($slot->status) ?>"><?= e($slot->status) ?></div>
                        <small><?= e($slot->note ?? 'Sin observacion') ?></small>
                    </div>
                <?php endforeach; ?>

                <?php if ($schedule === []): ?>
                    <p class="empty">No hay bloques cargados para este medico.</p>
                <?php endif; ?>
            </div>
        </article>
    </section>
</main>
</body>
</html>
