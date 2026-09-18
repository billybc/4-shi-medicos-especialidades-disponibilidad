# Razonamiento arquitectónico — Médicos, especialidades y disponibilidad

**Estudiante:** Billy Eduardo Cardona López
**Módulo:** Médicos, especialidades y disponibilidad

## 1. De dónde viene este diseño

Este documento conecta las decisiones tomadas en las semanas anteriores con la propuesta cliente-servidor de la semana 5:

- **Semana 1-2:** UML del flujo y aplicación del principio ISP sobre `DoctorAvailabilityRepositoryInterface`.
- **Semana 3:** separación en capas Presentation, Domain y Persistence del Micro-HIS Médicos (PHP 8.2+ vanilla, PDO con sentencias preparadas).
- **Semana 4:** patrón Repository con dos adaptadores (`PdoDoctorAvailabilityRepository`, `InMemoryDoctorAvailabilityRepository`) detrás de la misma interfaz, y un controlador sin SQL ni reglas de negocio.
- **Semana 5:** exponer ese mismo diseño como API REST (cliente-servidor) y evaluar si Disponibilidad debería convertirse en un microservicio.

## 2. Por qué las capas ya existentes hacen posible el paso a cliente-servidor

Como el controlador (`DoctorAvailabilityController`) nunca tuvo SQL ni reglas de negocio, agregar una interfaz HTTP no obliga a tocar el dominio: la capa de aplicación (`AvailabilityService`) sigue siendo la única que decide si un horario se solapa o si un médico puede registrarse. La API REST solo traduce peticiones HTTP hacia esos mismos métodos que ya existían.

## 3. Aplicación de principios SOLID en este límite

| Principio | Cómo se ve en el módulo |
|---|---|
| SRP | Cada clase (Doctor, AvailabilitySlot, AvailabilityService, cada Repository) tiene una única razón para cambiar. |
| OCP | Se puede agregar un adaptador nuevo (por ejemplo, uno HTTP hacia un servicio externo) sin modificar `AvailabilityService`. |
| ISP | `DoctorAvailabilityRepositoryInterface` agrupa solo los métodos que la capa de aplicación realmente necesita, sin forzar implementaciones innecesarias. |
| DIP | `AvailabilityService` depende de la interfaz `DoctorAvailabilityRepositoryInterface`, no de una implementación concreta. |

## 4. Decisión: monolito modular con API, no microservicio todavía

Se mantiene una sola aplicación desplegada, con la disponibilidad ya aislada detrás de su propia interfaz. Esto permite exponerla como si fuera un servicio independiente (contrato API definido en `semana-05-api-y-microservicio.md`) sin pagar el costo de una separación física real, hasta que exista evidencia medible de que se necesita (más de un consumidor, carga alta, necesidad de despliegue independiente).

## 5. Relación con el resto del Sistema Hospitalario Integrado

Otros módulos (citas, por ejemplo) consumirían la disponibilidad únicamente a través del contrato API, nunca contra la base de datos directamente. Esto es lo que permitiría, en el futuro, extraer Disponibilidad como servicio independiente sin romper a sus consumidores: el contrato se mantiene, solo cambia dónde vive la implementación.
