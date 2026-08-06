# Módulo 20 — Requerimientos Funcionales y No Funcionales

## Semana 2: RF/RNF y criterios de aceptación

### Requerimientos funcionales

| ID | Requerimiento | Caso de uso | Prioridad |
|---|---|---|---|
| RF-01 | Ver panel de resultados pendientes de validación | CU-01 | Alta |
| RF-02 | Ver detalle del resultado con valores y rangos de referencia | CU-02 | Alta |
| RF-03 | Validar un resultado, guardando `validated_by` y `validated_at` | CU-03 | Alta |
| RF-04 | Rechazar un resultado con motivo obligatorio | CU-04 | Alta |
| RF-05 | Confirmar un valor crítico y generar alerta | CU-05 | Media |
| RF-06 | Consultar historial de validaciones con filtros por fecha y paciente | CU-06 | Media |
| RF-07 | Revisar y revalidar un resultado corregido por el técnico | CU-07/CU-08 | Media |
| RF-08 | Restringir las acciones de validación al rol Bioquímico | — | Alta |
| RF-09 | Aislar los datos por tenant mediante `X-Tenant-ID` | — | Alta |
| RF-10 | Registrar trazabilidad de quién y cuándo se validó o rechazó | — | Alta |

### Requerimientos no funcionales

| ID | Categoría | Requerimiento |
|---|---|---|
| RNF-01 | Seguridad | Solo usuarios con rol Bioquímico y permisos correspondientes pueden validar o rechazar resultados. |
| RNF-02 | Seguridad | El acceso a los datos clínicos se protege con autenticación JWT y control por tenant. |
| RNF-03 | Integridad | Los valores clínicos ingresados se almacenan sin alteración; la validación es un registro inmutable de quién y cuándo. |
| RNF-04 | Rendimiento | El panel de resultados pendientes debe cargar en menos de 2 segundos con datos de un día laboral. |
| RNF-05 | Usabilidad | El flujo de validación requiere como máximo 3 clics por resultado. |
| RNF-06 | Auditabilidad | Toda validación o rechazo queda registrada para auditoría posterior. |
| RNF-07 | Disponibilidad | El módulo debe estar disponible durante el horario de laboratorio con respaldo diario de datos. |
| RNF-08 | Compatibilidad | La interfaz funciona en los navegadores modernos actualizados (Chrome, Edge, Firefox). |

### Criterios de aceptación

| ID | Criterio de aceptación |
|---|---|
| RF-01 | El bioquímico ve la lista de resultados con estado `pendiente` ordenados por prioridad y fecha. |
| RF-02 | El detalle muestra valores del resultado, rangos de referencia, flags anormal/crítico y datos del paciente. |
| RF-03 | Tras validar, el resultado muestra `validated_by`, `validated_at` y estado `validado`. |
| RF-04 | Un resultado rechazado sin motivo no se acepta; el motivo queda registrado y visible. |
| RF-05 | Al confirmar un valor crítico se crea una alerta en `critical_alerts`. |
| RF-06 | El historial permite filtrar por rango de fechas y paciente, y muestra el validador. |
| RF-07 | Un resultado corregido vuelve a estado `pendiente` y puede revalidarse (CU-08). |
| RF-08 | Un usuario sin rol Bioquímico recibe error 403 al intentar validar. |
| RF-09 | Un tenant no puede ver ni validar resultados de otro tenant. |
| RF-10 | Cada acción de validación o rechazo queda trazada con usuario y fecha. |

### Ejemplo de aplicación de la tabla RF/RNF

Ver `narrativa-alcance.md` para el contexto y `actores-casos-de-uso.md` para los casos de uso referenciados.
