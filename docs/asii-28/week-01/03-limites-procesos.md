# ASII-28 - Semana 1: Límites y procesos del módulo

## 1. Dentro del alcance

- Definir alcance, objetivos, riesgos y criterios de entrada y salida de cada ciclo.
- Convertir requisitos y criterios de aceptación en casos de prueba manuales trazables.
- Priorizar pruebas por criticidad clínica, seguridad, frecuencia de uso e impacto.
- Preparar datos, roles, tenants, ambiente y versión necesarios para ejecutar.
- Ejecutar pruebas smoke, funcionales, negativas, de permisos y de integración manual.
- Registrar estados `Aprobado`, `Fallido`, `Bloqueado` o `No ejecutado` con evidencia.
- Reportar defectos reproducibles y vincularlos con casos, requisitos y módulos.
- Re-probar correcciones sobre una versión identificada.
- Mantener la matriz de regresión y comunicar el estado funcional del HIS.
- Seleccionar escenarios estables que puedan compartirse con ASII-25 para automatización.

## 2. Fuera del alcance

- Implementar o corregir funcionalidades de los módulos clínicos y administrativos.
- Sustituir las pruebas unitarias que corresponden al desarrollador de cada módulo.
- Automatizar pruebas E2E, configurar CI o dirigir el despliegue final, responsabilidad
  de ASII-25.
- Definir unilateralmente reglas clínicas o criterios de aceptación de otros módulos.
- Aprobar resultados sin ambiente, versión, datos y evidencia identificables.
- Usar datos reales sensibles de pacientes fuera de los controles autorizados.
- Administrar infraestructura de producción o ejecutar cambios directos en ella.

## 3. Límites operativos

- La prueba solo evalúa funcionalidades disponibles en una versión o un commit concretos.
- Cada ejecución queda acotada al tenant y rol indicados en el caso de prueba.
- `Bloqueado` significa que se intentó ejecutar, pero una dependencia, dato o problema
  de ambiente impidió completar la prueba.
- `No ejecutado` significa que el caso no se intentó por alcance, prioridad o calendario.
- Un caso `Fallido` requiere resultado obtenido y evidencia suficiente para reproducirlo.
- Una corrección no se considera verificada hasta completar la re-prueba.
- La regresión se actualiza cuando cambia un requisito, aparece un defecto relevante o
  se integra un nuevo flujo.

## 4. Procesos principales

### P-01 - Planificar el ciclo de pruebas

1. Identificar versión, módulos disponibles y cambios incluidos.
2. Revisar requisitos, criterios de aceptación, dependencias y riesgos.
3. Seleccionar alcance, tipos de prueba y criterios de entrada y salida.
4. Definir responsables, ambiente, datos y calendario de ejecución.

**Salida:** ciclo de pruebas con alcance y condiciones identificadas.

### P-02 - Diseñar y mantener casos manuales

1. Relacionar cada escenario con un requisito, criterio o riesgo.
2. Definir precondiciones, rol, tenant, datos y pasos reproducibles.
3. Especificar un resultado esperado observable.
4. Asignar prioridad e incorporar el caso a la matriz cuando corresponda.

**Salida:** caso de prueba revisable y trazable.

### P-03 - Preparar ambiente y datos

1. Confirmar ambiente, versión o commit bajo prueba.
2. Verificar que los servicios y dependencias necesarios estén disponibles.
3. Crear o identificar tenants, usuarios, roles y datos controlados.
4. Registrar cualquier condición que pueda bloquear la ejecución.

**Salida:** ambiente listo o bloqueo documentado.

### P-04 - Ejecutar y registrar resultados

1. Ejecutar los pasos sin omitir precondiciones.
2. Comparar el comportamiento observado con el esperado.
3. Asignar estado `Aprobado`, `Fallido`, `Bloqueado` o `No ejecutado`.
4. Adjuntar evidencia y registrar ambiente, versión, fecha y ejecutor.

**Salida:** resultado verificable asociado al caso.

### P-05 - Gestionar defectos

1. Confirmar que el fallo es reproducible y no se debe al ambiente o datos incorrectos.
2. Registrar pasos, resultado esperado, resultado obtenido, evidencia y severidad.
3. Vincular el defecto con caso, requisito, módulo y versión afectada.
4. Comunicarlo al responsable del módulo sin modificar su código sin coordinación.

**Salida:** defecto accionable y trazable.

### P-06 - Re-probar una corrección

1. Recibir del responsable la versión donde se aplicó la corrección.
2. Repetir el caso que originó el defecto con condiciones equivalentes.
3. Confirmar la corrección o reabrir el defecto con nueva evidencia.
4. Ejecutar casos relacionados cuando exista riesgo de efecto secundario.

**Salida:** corrección verificada o defecto reabierto.

### P-07 - Ejecutar y actualizar la regresión

1. Seleccionar casos activos según riesgo y cambios de la versión.
2. Ejecutar primero smoke y flujos críticos; después la regresión aplicable.
3. Consolidar aprobados, fallidos, bloqueados y no ejecutados.
4. Actualizar la matriz y comunicar riesgos pendientes.

**Salida:** matriz actualizada y estado funcional respaldado por evidencia.
