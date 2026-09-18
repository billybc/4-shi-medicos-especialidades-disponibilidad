# Semana 10 - Diseño para movilidad

## Objetivo móvil

Adaptar el flujo a pantallas de 320 a 430 px, priorizando una tarea por pantalla, lectura rápida de disponibilidad y recuperación ante errores de conexión.

## Pantallas propuestas

### Pantalla 1 - Lista de médicos

- Encabezado compacto.
- Búsqueda a ancho completo.
- Filtro de especialidad en un control desplegable.
- Una tarjeta por médico con nombre, especialidad y acción `Ver`.
- Sin tablas horizontales.

### Pantalla 2 - Registro de médico

- Campos en una sola columna.
- Especialidad después de datos básicos.
- Botón `Guardar` fijo al final del formulario, no flotante sobre contenido.
- Confirmación antes de abandonar si hay cambios.

### Pantalla 3 - Disponibilidad

- Selector de médico y fecha arriba.
- Bloques mostrados como tarjetas verticales.
- Hora, estado y acción en ese orden.
- Conflicto mostrado debajo del campo de hora, no solamente como alerta global.

### Pantalla 4 - Resultado de conflicto

- Mensaje específico.
- Resumen del bloque existente.
- Acción primaria `Cambiar horario`.
- Acción secundaria `Cancelar`.
- El formulario conserva los datos introducidos.

## Breakpoints

| Ancho | Regla |
|---:|---|
| `320-359px` | Una columna, botones a ancho completo, sin información secundaria. |
| `360-430px` | Una columna, tarjetas con acciones alineadas al final. |
| `431-767px` | Una columna amplia; se permite agrupar fecha y estado si caben. |
| `768px o más` | Dos columnas para formulario y agenda, manteniendo el orden de lectura. |

## Jerarquía de contenido

1. Médico seleccionado.
2. Especialidad.
3. Fecha consultada.
4. Bloques y estado.
5. Acciones permitidas.
6. Observaciones secundarias.

## Escenario móvil 1 - Camino feliz

Una recepcionista abre la lista en un teléfono de 360 px, busca `Andrea`, selecciona el médico, elige una fecha y consulta. El sistema muestra dos bloques disponibles en tarjetas. La acción principal es consultar; no se muestran controles de edición porque el rol solo tiene permiso de lectura.

**Decisión:** ocultar acciones no autorizadas y mantener visible la información necesaria para agendar.

## Escenario móvil 2 - Conexión limitada y conflicto

Un administrador registra un bloque de 10:00 a 13:00, pero ya existe uno de 08:00 a 12:00. El servidor devuelve conflicto o la red falla.

**Decisiones:**

- El botón cambia a `Guardando...` para evitar doble envío.
- El formulario mantiene fecha y horas.
- El mensaje explica el solapamiento.
- Se ofrece `Cambiar horario` o `Reintentar`.
- Si la respuesta no se confirma, no se muestra éxito falso.

## Reglas de interacción

- Todos los controles táctiles deben medir al menos 44x44 px.
- El foco debe ser visible también con teclado externo.
- El texto no debe depender únicamente del color.
- Los errores deben aparecer junto al campo y resumirse arriba.
- No usar desplazamiento horizontal para leer una agenda.
- Mantener una acción primaria por pantalla.
- Anunciar carga y resultado mediante una región `aria-live`.
