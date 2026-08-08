# Módulo 20 — Validación de resultados por bioquímico

## Alcance

El módulo **"Validación de resultados por bioquímico"** se encarga del flujo posterior al ingreso de resultados de laboratorio. Inicia cuando el técnico de laboratorio ha ingresado uno o más resultados en el sistema y estos quedan en estado `pendiente`. El bioquímico accede a un panel donde visualiza los resultados pendientes, revisa los valores obtenidos contra los rangos de referencia, y decide si valida (acepta) o rechaza cada resultado.

Si valida, el resultado se marca como `validado` con su `validated_by` y `validated_at`, y queda disponible para que el médico lo consulte en el expediente del paciente. Si rechaza, debe ingresar un motivo y el resultado regresa al técnico para corrección.

Adicionalmente, si un resultado supera umbrales críticos, el sistema debe generar una alerta en la tabla `critical_alerts` y notificar al médico tratante.

## Límites

- No cubre el ingreso de resultados (módulo #19 — Keily Orellana)
- No cubre el envío de notificaciones en tiempo real (módulo #21 — Dulce Prado)
- No cubre la orden de laboratorio desde el EMR (módulo #16 — Mercedes López)

## Dependencias

- Módulo #19 (Ingreso de resultados) — consume resultados pendientes
- Módulo #21 (Alertas críticas) — consume alertas generadas
- Módulo #17 (Catálogo de pruebas) — rangos de referencia
