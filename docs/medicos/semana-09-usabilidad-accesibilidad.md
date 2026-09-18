# Semana 9 - Evaluación de usabilidad y accesibilidad

## Criterio de evaluación

Se revisó el flujo de registro de médico, asociación de especialidad y consulta de disponibilidad con heurísticas de Nielsen y criterios WCAG 2.2 AA. La prioridad considera impacto en la operación y riesgo de error de agenda.

## Checklist

| Criterio | Estado | Evidencia o corrección |
|---|---|---|
| Navegación completa con teclado | Parcial | Agregar orden lógico y probar `Tab`, `Shift+Tab` y `Enter`. |
| Foco visible | Pendiente | Definir `:focus-visible` con contraste mínimo. |
| Etiquetas asociadas a campos | Parcial | Cada `label` debe apuntar a un `id` único. |
| Mensajes junto al campo | Parcial | Mostrar qué corregir sin depender solo del color. |
| Contraste de texto | Pendiente | Revisar textos secundarios y badges contra fondo. |
| Error de conflicto comprensible | Cumple | Indica bloque existente y acción para corregir. |
| Estado de carga | Parcial | Deshabilitar acción y anunciar carga. |
| Estado vacío accionable | Cumple | Incluye acción para registrar o cambiar fecha. |
| Prevención de pérdida de datos | Pendiente | Confirmar salida si el formulario fue modificado. |
| Protección de datos | Cumple | No se muestran pacientes ni datos clínicos. |
| Tamaño de objetivo táctil | Pendiente | Mantener controles accionables de al menos 44x44 px. |
| Lectores de pantalla | Parcial | Usar `aria-live` para éxito y errores. |

## Seis hallazgos priorizados

| ID | Hallazgo | Impacto | Prioridad | Corrección propuesta | Criterio verificable |
|---|---|---|---|---|---|
| H-01 | El foco no está definido visualmente en todos los controles. | Alto | P0 | Agregar estilo `:focus-visible`. | Cada control enfocado se distingue sin usar solo color. |
| H-02 | Un conflicto puede presentarse como mensaje general. | Alto | P0 | Mostrar fecha, intervalo existente y acción `Cambiar horario`. | Una persona identifica qué intervalo debe modificar. |
| H-03 | El estado de carga no bloquea doble envío. | Alto | P1 | Deshabilitar botón y mostrar progreso durante la petición. | Un doble clic genera una sola solicitud. |
| H-04 | Algunos campos dependen de placeholder como ayuda. | Medio | P1 | Mantener etiqueta visible y agregar ayuda contextual. | El campo se entiende sin placeholder. |
| H-05 | Estados de disponibilidad dependen del color. | Medio | P1 | Añadir texto y etiqueta, por ejemplo `Disponible`. | La información se entiende en escala de grises. |
| H-06 | La interfaz no define recuperación ante conexión limitada. | Medio | P1 | Conservar formulario y ofrecer `Reintentar`. | El usuario puede reintentar sin volver a escribir. |

## Backlog priorizado

### P0 - Antes de presentar

- H-01: foco visible y navegación por teclado.
- H-02: conflicto específico y recuperable.

### P1 - Siguiente iteración

- H-03: evitar doble envío.
- H-04: etiquetas y ayuda contextual.
- H-05: texto además de color.
- H-06: reintento y conservación de datos.

## Pruebas manuales

1. Usar solamente teclado y completar registro de médico.
2. Verificar que el foco avance en orden visual.
3. Forzar un horario superpuesto y confirmar que el error indique la causa.
4. Reducir el ancho a 320 px y comprobar que no haya desplazamiento horizontal.
5. Revisar los mensajes con lector de pantalla o inspección de `aria-live`.
6. Verificar contraste con una herramienta WCAG AA.
