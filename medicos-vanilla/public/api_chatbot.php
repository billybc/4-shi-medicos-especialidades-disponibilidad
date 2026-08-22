<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$opcion = $input['opcion'] ?? '';

// Subir un nivel desde public/ hacia storage/medicos.sqlite
$dbPath = dirname(__DIR__) . '/storage/medicos.sqlite';

if (!file_exists($dbPath)) {
    echo json_encode(['respuesta' => 'No se encontró la base de datos en: ' . $dbPath]);
    exit;
}

try {
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo json_encode(['respuesta' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}

switch ($opcion) {
    case 'especialidades':
        try {
            $stmt = $pdo->query("SELECT DISTINCT specialty FROM doctors WHERE specialty IS NOT NULL AND active = 1");
            $items = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $texto = !empty($items) ? "<b>Especialidades disponibles:</b><br>• " . implode('<br>• ', $items) : "No hay especialidades registradas.";
        } catch (Exception $e) {
            $texto = "Error al consultar especialidades.";
        }
        echo json_encode(['respuesta' => $texto]);
        break;

    case 'medicos':
        try {
            $stmt = $pdo->query("SELECT full_name, specialty FROM doctors WHERE active = 1");
            $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $lineas = [];
            foreach ($doctors as $doc) {
                $lineas[] = "<b>Dr. {$doc['full_name']}</b> ({$doc['specialty']})";
            }
            $texto = !empty($lineas) ? "<b>Nuestros Médicos:</b><br>• " . implode('<br>• ', $lineas) : "No hay médicos activos registrados.";
        } catch (Exception $e) {
            $texto = "Error al consultar la lista de médicos.";
        }
        echo json_encode(['respuesta' => $texto]);
        break;

    case 'disponibilidad':
        try {
            $stmt = $pdo->query("SELECT d.full_name, a.* FROM availability_slots a JOIN doctors d ON d.id = a.doctor_id LIMIT 5");
            $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $lineas = [];
            foreach ($slots as $s) {
                $fecha = $s['date'] ?? $s['slot_date'] ?? $s['created_at'] ?? '';
                $inicio = $s['start_time'] ?? $s['start'] ?? '';
                $fin = $s['end_time'] ?? $s['end'] ?? '';
                $lineas[] = "<b>Dr. {$s['full_name']}:</b> {$fecha} ({$inicio} - {$fin})";
            }
            $texto = !empty($lineas) ? "<b>Horarios Disponibles:</b><br>• " . implode('<br>• ', $lineas) : "No hay bloques de disponibilidad registrados.";
        } catch (Exception $e) {
            $texto = "Consulte la sección de horarios en el panel principal.";
        }
        echo json_encode(['respuesta' => $texto]);
        break;

    default:
        echo json_encode(['respuesta' => 'Selecciona una opción válida.']);
        break;
}