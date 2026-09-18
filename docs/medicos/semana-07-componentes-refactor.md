# Semana 7 - Diseño de componentes y refactorización

**Módulo:** Médicos, especialidades y disponibilidad  
**Flujo:** registro de médico, asociación de especialidad y consulta de disponibilidad

## Objetivo

Separar la interfaz, los casos de uso, las reglas del dominio y la persistencia sin ampliar el alcance del módulo.

## Componentes backend

| Componente | Responsabilidad | Entrada | Salida |
|---|---|---|---|
| `DoctorController` | Traducir HTTP del registro de médico | JSON del médico | `201` y médico creado |
| `SpecialtyController` | Asociar especialidad | `doctorId`, `specialtyId` | Asociación confirmada |
| `AvailabilityController` | Registrar y consultar bloques | Fechas y horas | Disponibilidad o error |
| `DoctorRegistrationService` | Validar y coordinar registro | Datos del médico | `Doctor` |
| `SpecialtyAssignmentService` | Validar asociación | IDs | Asociación |
| `AvailabilityService` | Validar horas y solapamientos | Bloque | `AvailabilitySlot` |
| `DoctorAvailabilityRepositoryInterface` | Contrato de persistencia | Consultas y comandos | Entidades |
| `PdoDoctorAvailabilityRepository` | Persistencia SQLite/PDO | Entidades | Filas persistidas |

## Componentes frontend

| Componente | Responsabilidad |
|---|---|
| `DoctorListView` | Lista y selecciona médicos. |
| `DoctorForm` | Captura datos del médico. |
| `SpecialtyAssignmentForm` | Asocia especialidades. |
| `AvailabilityForm` | Captura fecha, inicio, fin y estado. |
| `AvailabilityTimeline` | Muestra bloques del médico. |
| `FeedbackBanner` | Presenta carga, éxito y errores recuperables. |
| `PermissionGuard` | Oculta acciones no autorizadas. |
| `ApiClient` | Centraliza HTTP, JWT y errores. |

## Contratos de entrada y salida

### Registro de médico

```json
{
  "license_number": "MED-0001",
  "full_name": "Andrea Lopez",
  "specialty_id": 2
}
```

```json
{
  "data": {
    "id": 1,
    "full_name": "Andrea Lopez",
    "license_number": "MED-0001",
    "status": "active"
  }
}
```

### Consulta de disponibilidad

```http
GET /api/v1/doctors/1/availability?date=2026-09-21
```

```json
{
  "data": [
    {
      "id": 10,
      "date": "2026-09-21",
      "start_time": "08:00",
      "end_time": "12:00",
      "status": "available"
    }
  ]
}
```

## Refactor concreto

### Antes

`DoctorAvailabilityController` recibía la petición, decidía el flujo de POST, llamaba directamente al servicio y servía una vista que contiene registro de médico y disponibilidad en la misma pantalla. La acción `register_doctor` también depende de una condición especial en la petición.

Problemas:

- Alto acoplamiento entre registro de médico y disponibilidad.
- Difícil probar cada caso de uso por separado.
- La UI presenta dos intenciones distintas en el mismo formulario.
- Agregar asociación de especialidad aumentaría las condiciones del controlador.

### Después

```text
DoctorListView -> DoctorApiClient -> DoctorController -> DoctorRegistrationService -> Repository
SpecialtyAssignmentForm -> ApiClient -> SpecialtyController -> SpecialtyAssignmentService
AvailabilityForm -> ApiClient -> AvailabilityController -> AvailabilityService -> Repository
AvailabilityTimeline <- ApiClient <- AvailabilityController
```

El controlador solo transforma entrada HTTP y salida JSON. La regla de solapamiento permanece en `AvailabilityService`; el Repository continúa siendo el límite de persistencia.

## Justificación

El cambio reduce el acoplamiento sin crear un nuevo módulo clínico. Cada caso de uso tiene una entrada, una salida y una prueba independiente. La implementación vanilla existente puede migrar gradualmente: primero se separan formularios y acciones, luego se exponen los controladores REST.

## Criterios de aceptación

- [ ] Registro, asociación y disponibilidad tienen contratos independientes.
- [ ] La UI no ejecuta SQL ni aplica reglas de solapamiento.
- [ ] El controlador no contiene consultas SQL.
- [ ] La validación de conflicto se conserva en `AvailabilityService`.
- [ ] Se puede probar el caso de uso con `InMemoryDoctorAvailabilityRepository`.
