# ASII-28 - Semana 1: Casos de uso

## Catálogo

| Código | Caso de uso | Actor principal | Descripción breve |
|---|---|---|---|
| CU-01 | Diseñar y vincular casos manuales | Analista QA | Convierte requisitos o riesgos en precondiciones, pasos, datos ficticios y resultados esperados. |
| CU-02 | Planificar campaña de regresión | Analista QA | Define alcance, versión, ambiente, prioridad y casos aplicables de la campaña. |
| CU-03 | Validar precondiciones | Analista QA | Comprueba que ambiente, versión, tenant, rol y datos estén disponibles antes de ejecutar. |
| CU-04 | Ejecutar campaña de regresión | Analista QA | Ejecuta sobre el HIS los casos aplicables y compara resultados observados y esperados. |
| CU-05 | Registrar resultado y evidencia | Analista QA | Conserva estado, versión, ambiente, datos, resultado y evidencia de cada ejecución. |
| CU-06 | Gestionar defecto reproducible | Analista QA | Documenta un fallo reproducible y lo comunica al Responsable del módulo. |
| CU-07 | Re-probar una corrección | Analista QA | Repite el caso sobre la versión corregida y cierra o reabre el defecto con evidencia. |
| CU-08 | Consultar trazabilidad y cerrar campaña | Analista QA | Consolida cobertura, resultados, defectos, bloqueos y evidencias para revisión. |

## Precondiciones generales

- Existe un requisito, criterio de aceptación o riesgo que justifica el caso.
- La versión y el ambiente bajo prueba están identificados.
- Se dispone de tenant, rol y datos ficticios cuando el escenario los requiere.
- El ejecutor tiene autorización para acceder a la funcionalidad evaluada.

## Postcondiciones generales

- Toda ejecución conserva un estado y su evidencia.
- Los fallos reproducibles quedan vinculados con el caso y el módulo responsable.
- Las re-pruebas identifican la versión donde se aplicó la corrección.
- El cierre de campaña presenta cobertura, bloqueos y riesgos pendientes.

## Flujos resumidos

### CU-04 - Ejecutar campaña de regresión

**Flujo principal:**

1. QA selecciona un caso aplicable a la campaña.
2. Valida las precondiciones mediante CU-03.
3. Ejecuta los pasos sobre el HIS con datos ficticios.
4. Compara el resultado observado con el esperado.
5. Registra el resultado y la evidencia mediante CU-05.
6. Continúa hasta terminar los casos aplicables.

**Flujos alternos:**

- Si faltan ambiente, versión o datos, la campaña queda bloqueada con su causa.
- Si el resultado es diferente, QA repite las condiciones relevantes.
- Si el fallo no puede reproducirse, registra un bloqueo y no confirma un defecto.
- Si el fallo es reproducible, inicia CU-06.

### CU-06 - Gestionar defecto reproducible

**Flujo principal:**

1. QA confirma que el fallo puede reproducirse.
2. Registra versión, ambiente, tenant, rol y datos utilizados.
3. Documenta pasos, resultado esperado, resultado observado y evidencia.
4. Vincula el defecto con el caso y el módulo responsable.
5. Notifica al Responsable del módulo.

**Flujo alterno:**

- Si el comportamiento coincide con un criterio aprobado, QA actualiza el caso y no
  registra un defecto.
- Si la causa pertenece al ambiente, QA registra un bloqueo en lugar de atribuir un
  defecto al módulo.

### CU-07 - Re-probar una corrección

**Precondición específica:** el Responsable del módulo notificó la versión corregida.

**Flujo principal:**

1. QA prepara condiciones equivalentes a las del fallo original.
2. Ejecuta nuevamente el caso sobre la versión corregida.
3. Registra el resultado y una nueva evidencia mediante CU-05.
4. Cierra el defecto si la corrección queda verificada.

**Flujo alterno:**

- Si el fallo persiste, QA reabre el defecto con la nueva versión y evidencia.
- Si faltan condiciones para re-probar, mantiene el defecto bloqueado y documenta la
  causa.

### CU-08 - Consultar trazabilidad y cerrar campaña

**Flujo principal:**

1. QA verifica que todos los casos aplicables tengan estado.
2. Consolida aprobados, fallidos, bloqueados y no ejecutados.
3. Relaciona casos, ejecuciones, evidencias y defectos.
4. Calcula la cobertura y documenta riesgos pendientes.
5. Entrega el resumen al Revisor de calidad y cierra la campaña.
