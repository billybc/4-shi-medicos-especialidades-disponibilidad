# Razonamiento arquitectonico

## Decisiones base

El parcial se resuelve como una aplicacion monolitica pequena, ejecutable en local con PHP 8.2 vanilla. No se usa framework porque el objetivo es mostrar capas claras, PDO y SQLite sin ocultar la arquitectura detras de un contenedor pesado.

## Responsabilidades por capa

### Presentacion

- Recibe HTTP desde el navegador.
- Lee GET y POST.
- Muestra formularios, mensajes y listados.
- No contiene SQL ni reglas de negocio.

### Aplicacion

- Orquesta el caso de uso.
- Valida reglas de flujo como fechas, horas y cruces.
- Decide si el horario puede registrarse o debe rechazarse.

### Dominio

- Define las entidades Doctor y AvailabilitySlot.
- Expone el contrato del repositorio.
- Conserva la regla central de negocio: un medico no puede tener bloques solapados en la misma fecha, salvo estados bloqueados definidos por la aplicacion.

### Persistencia

- Encapsula PDO y SQLite.
- Ejecuta las consultas SQL.
- Inicializa schema y datos ficticios.

## Monolito y frontera distribuida evaluada

Se inicia con un monolito modular expuesto como API REST. Esta decision conserva la ejecucion local y evita introducir complejidad distribuida antes de tener una necesidad medible.

La frontera candidata es `Availability Service`, responsable de crear y consultar bloques y validar solapamientos. No se extrae en esta etapa: se evaluara cuando existan varios consumidores, una carga que requiera escalamiento independiente o una necesidad real de despliegue separado. El contrato y la responsabilidad quedan documentados para permitir una migracion gradual.

## Contrato de repositorio

La interfaz `DoctorAvailabilityRepositoryInterface` desacopla la aplicacion del motor de base de datos. Si el docente pide migrar de SQLite a otro motor, solo cambia la implementacion de persistencia.
