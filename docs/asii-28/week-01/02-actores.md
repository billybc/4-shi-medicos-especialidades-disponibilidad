# ASII-28 - Semana 1: Actores del módulo

## Actores identificados

| Actor | Tipo | Participación en el módulo |
|---|---|---|
| Analista QA | Primario, humano | Diseña casos manuales, planifica y ejecuta campañas, registra evidencia, reporta defectos, realiza re-pruebas y mantiene la trazabilidad. |
| Responsable del módulo | Secundario, humano | Recibe defectos reproducibles, implementa correcciones y notifica la versión disponible para re-prueba. |
| Revisor de calidad | Secundario, humano | Consulta cobertura, resultados, defectos, bloqueos y evidencias para revisar el cierre de la campaña. |
| HIS bajo prueba | Secundario, sistema externo | Expone el comportamiento funcional que QA ejecuta y compara con el resultado esperado. |

## Responsabilidades y colaboración

| Actividad | Analista QA | Responsable del módulo | Revisor | HIS bajo prueba |
|---|---:|---:|---:|---:|
| Diseñar casos manuales | Responsable | Consultado | Informado | No participa |
| Planificar la campaña | Responsable | Consultado | Informado | No participa |
| Validar precondiciones | Responsable | Apoyo | Informado | Provee disponibilidad |
| Ejecutar la regresión | Responsable | Informado | Informado | Sistema evaluado |
| Registrar evidencia | Responsable | Informado | Revisa | Provee el resultado observado |
| Gestionar defectos | Reporta | Corrige | Informado | Sistema afectado |
| Re-probar correcciones | Responsable | Apoyo | Informado | Sistema evaluado |
| Cerrar la campaña | Responsable | Informado | Revisa | No participa |

## Aclaraciones

- El Analista QA confirma resultados; no modifica el código de otros módulos sin
  coordinación.
- El Responsable del módulo corrige el defecto y QA verifica la corrección mediante una
  re-prueba independiente.
- El Revisor no ejecuta la campaña: comprueba que las conclusiones tengan trazabilidad y
  evidencia.
- Los datos usados durante las pruebas deben ser ficticios y no identificables.
