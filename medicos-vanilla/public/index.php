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
<!-- Widget Messenger para Vanilla PHP -->
<style>
  #messenger-btn {
    position: fixed; bottom: 25px; right: 25px; width: 60px; height: 60px;
    background: linear-gradient(135deg, #0084FF, #00C6FF); color: white;
    border-radius: 50%; border: none; cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 132, 255, 0.4); z-index: 99999;
    display: flex; align-items: center; justify-content: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  #messenger-btn:hover { transform: scale(1.1); box-shadow: 0 6px 20px rgba(0, 132, 255, 0.6); }

  #messenger-box {
    display: none; position: fixed; bottom: 95px; right: 25px; width: 350px; height: 480px;
    background-color: #FFFFFF; border-radius: 18px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2); flex-direction: column;
    overflow: hidden; z-index: 99999; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    border: 1px solid rgba(0,0,0,0.1);
  }

  .msg-header {
    background: #FFFFFF; color: #050505; padding: 12px 16px; font-weight: 600;
    display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px solid #E4E6EB;
  }
  .msg-header-info { display: flex; align-items: center; gap: 10px; }
  .avatar-container { position: relative; }
  .avatar { width: 38px; height: 38px; background: #0084FF; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; }
  .status-dot { position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background: #31A24C; border: 2px solid white; border-radius: 50%; }
  .msg-title { font-size: 15px; font-weight: 700; color: #050505; display: block; }
  .msg-subtitle { font-size: 12px; color: #65676B; display: block; }
  .close-btn { background: #E4E6EB; border: none; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; color: #050505; font-weight: bold; }

  .msg-body { flex: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 8px; background: #FFFFFF; }
  .bubble { max-width: 80%; padding: 10px 14px; border-radius: 18px; font-size: 14px; line-height: 1.4; }
  .bubble-bot { background: #F0F2F5; color: #050505; align-self: flex-start; border-bottom-left-radius: 4px; }
  .bubble-user { background: #0084FF; color: #FFFFFF; align-self: flex-end; border-bottom-right-radius: 4px; }

  .msg-footer { padding: 10px; display: flex; flex-wrap: wrap; gap: 6px; background: #FFFFFF; border-top: 1px solid #F0F2F5; }
  .quick-reply { background: #FFFFFF; border: 1px solid #0084FF; color: #0084FF; padding: 8px 12px; border-radius: 18px; font-size: 12px; font-weight: 600; cursor: pointer; }
  .quick-reply:hover { background: #0084FF; color: #FFFFFF; }
</style>

<!-- Botón Flotante Messenger -->
<button id="messenger-btn" onclick="toggleMessenger()">
  <svg width="28" height="28" viewBox="0 0 24 24" fill="white">
    <path d="M12 2C6.477 2 2 6.145 2 11.258c0 2.91 1.455 5.51 3.733 7.182V22l3.418-1.876c.91.253 1.873.39 2.849.39 5.523 0 10-4.145 10-9.256S17.523 2 12 2zm1.09 12.392l-2.587-2.76-5.05 2.76 5.552-5.897 2.65 2.76 4.986-2.76-5.551 5.897z"/>
  </svg>
</button>

<!-- Caja del Chat -->
<div id="messenger-box">
  <div class="msg-header">
    <div class="msg-header-info">
      <div class="avatar-container">
        <div class="avatar">🏥</div>
        <div class="status-dot"></div>
      </div>
      <div>
        <span class="msg-title">Asistente Médico</span>
        <span class="msg-subtitle">En línea</span>
      </div>
    </div>
    <button class="close-btn" onclick="toggleMessenger()">✕</button>
  </div>

  <div class="msg-body" id="msg-body">
    <div class="bubble bubble-bot">¡Hola! 👋 Consulta la disponibilidad de nuestros médicos y especialidades en tiempo real.</div>
  </div>

  <div class="msg-footer">
    <button class="quick-reply" onclick="consultarBot('especialidades', 'Especialidades')">Especialidades</button>
    <button class="quick-reply" onclick="consultarBot('medicos', 'Médicos')">Médicos</button>
    <button class="quick-reply" onclick="consultarBot('disponibilidad', 'Disponibilidad')">Disponibilidad</button>
  </div>
</div>

<script>
  function toggleMessenger() {
    const box = document.getElementById('messenger-box');
    box.style.display = (box.style.display === 'flex') ? 'none' : 'flex';
  }

  async function consultarBot(clave, etiqueta) {
    const chat = document.getElementById('msg-body');

    chat.innerHTML += `<div class="bubble bubble-user">${etiqueta}</div>`;
    chat.scrollTop = chat.scrollHeight;

    try {
      const response = await fetch('api_chatbot.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ opcion: clave })
      });

      const data = await response.json();
      chat.innerHTML += `<div class="bubble bubble-bot">${data.respuesta}</div>`;
    } catch (error) {
      chat.innerHTML += `<div class="bubble bubble-bot">Error al conectar con la base de datos SQLite.</div>`;
    }

    chat.scrollTop = chat.scrollHeight;
  }
</script>
</body>
</html>
