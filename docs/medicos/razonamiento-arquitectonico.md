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

## Monolito o frontera distribuida

Se elige monolito. Para este parcial no hay necesidad de microservicios ni de una frontera distribuida, porque el alcance es pequeno, la ejecucion debe ser local y el costo de coordinacion superaria el valor pedagogico. La frontera queda en capas internas, no en servicios remotos.

## Contrato de repositorio

La interfaz `DoctorAvailabilityRepositoryInterface` desacopla la aplicacion del motor de base de datos. Si el docente pide migrar de SQLite a otro motor, solo cambia la implementacion de persistencia.
