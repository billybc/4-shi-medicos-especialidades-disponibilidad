# ASII-24 — Actividad, secuencia y trazabilidad

## 1. Propósito del documento

Esta evidencia completa la Semana 1 con el modelado de actividad, secuencia y trazabilidad del centro de documentación y manuales por rol. El contenido separa capacidades base ya verificadas del HIS y comportamiento propuesto para ASII-24.

## 2. Relación con el alcance aprobado

El alcance aprobado cubre publicación, consulta, visualización, descarga, actualización y archivado documental dentro del tenant activo. El control de acceso con JWT, tenant y RBAC pertenece a la base existente del HIS y es consumido por el diseño propuesto.

## 3. Diagrama UML de actividad

El diagrama fuente reproducible está en [diagrams/activity-diagram.puml](diagrams/activity-diagram.puml). No se generó SVG porque no hay un renderer PlantUML local compatible disponible en este entorno.

## 4. Decisiones, excepciones y resultados del flujo

- `ACT-ACC-01` valida el JWT o contexto de autenticación.
- `ACT-ACC-02` verifica existencia y coincidencia del tenant.
- `ACT-ACC-03` obtiene los roles del usuario autenticado.
- `ACT-PUB-01` confirma que el actor tenga rol `Admin`.
- `ACT-PUB-04` valida la información de publicación.
- `ACT-PUB-05` almacena archivo o resuelve referencia.
- `ACT-PUB-06` registra documento, tenant, roles y versión.
- `ACT-UPD-03` valida la actualización antes de persistirla.
- `ACT-ARC-03` marca el documento como archivado.
- `ACT-CON-06` verifica existencia, disponibilidad y acceso.
- `ACT-EX-01` a `ACT-EX-06` cubren autenticación inválida, tenant no coincidente, acceso administrativo denegado, validación incorrecta, fallo de almacenamiento/persistencia y documento no autorizado.

Resultados modelados:

- acceso autorizado y operación confirmada;
- corrección de datos ante validación fallida;
- rechazo seguro ante autenticación, tenant o autorización inválidos;
- estado vacío cuando no hay documentación disponible.

## 5. Participantes del diagrama de secuencia

- `User`: `Admin` o usuario autenticado.
- `UI`: interfaz web de documentación, propuesta ASII-24.
- `API`: API de documentación, propuesta ASII-24.
- `Access`: JWT / Tenant / RBAC, base existente del HIS.
- `Service`: servicio de documentación, propuesta ASII-24.
- `DB`: persistencia de documentación, propuesta ASII-24.
- `Storage`: almacenamiento de archivos, propuesta ASII-24.

## 6. Diagrama UML de secuencia

El diagrama fuente reproducible está en [diagrams/sequence-diagram.puml](diagrams/sequence-diagram.puml). No se generó SVG porque no hay un renderer PlantUML local compatible disponible en este entorno.

## 7. Validaciones y respuestas modeladas

- `SEQ-ACC-01` envía JWT y `X-Tenant-ID`.
- `SEQ-ACC-02` valida identidad, tenant y roles.
- `SEQ-ACC-03` retorna contexto autorizado o rechazo.
- `SEQ-PUB-03` valida información de publicación.
- `SEQ-PUB-04` almacena archivo o resuelve referencia.
- `SEQ-PUB-05` registra metadatos, tenant, roles y versión.
- `SEQ-PUB-06` retorna confirmación de publicación.
- `SEQ-UPD-03` valida la actualización.
- `SEQ-UPD-04` almacena la nueva versión.
- `SEQ-UPD-05` registra la actualización.
- `SEQ-UPD-06` retorna confirmación.
- `SEQ-ARC-03` marca el documento como archivado.
- `SEQ-ARC-04` retorna confirmación.
- `SEQ-CON-03` consulta por tenant, disponibilidad y roles.
- `SEQ-CON-04` retorna lista autorizada o estado vacío.
- `SEQ-CON-07` recupera contenido o archivo.
- `SEQ-CON-08` entrega visualización o descarga.

Respuestas de excepción modeladas:

- `SEQ-EX-01` rechazo por autenticación inválida.
- `SEQ-EX-02` rechazo por tenant inválido o no coincidente.
- `SEQ-EX-03` rechazo por rol insuficiente.
- `SEQ-EX-04` respuesta de validación incorrecta.
- `SEQ-EX-05` respuesta por documento inexistente, archivado o no autorizado.
- `SEQ-EX-06` respuesta por fallo de almacenamiento o persistencia.

## 8. Matriz de trazabilidad

| Caso de uso | Actor | Actividades relacionadas | Mensajes de secuencia relacionados | Resultado trazable |
|---|---|---|---|---|
| UC-01 Consultar documentación disponible | Usuario autenticado del HIS | `ACT-ACC-01..03`, `ACT-CON-01..03` | `SEQ-ACC-01..03`, `SEQ-CON-01..04` | Lista autorizada o estado vacío |
| UC-02 Buscar y filtrar documentación | Usuario autenticado del HIS | `ACT-CON-04` | `SEQ-CON-01`, `SEQ-CON-03`, `SEQ-CON-04` | Resultados filtrados dentro del tenant |
| UC-03 Visualizar documento o manual | Usuario autenticado del HIS | `ACT-CON-05..08` | `SEQ-CON-05..08` | Contenido visualizado solo si está autorizado |
| UC-04 Descargar documento o manual | Usuario autenticado del HIS | `ACT-CON-05..08` | `SEQ-CON-05..08` | Archivo entregado solo si está autorizado |
| UC-05 Publicar documento o manual | `Admin` | `ACT-ACC-01..03`, `ACT-PUB-01..07` | `SEQ-ACC-01..03`, `SEQ-PUB-01..06` | Publicación confirmada dentro del tenant activo |
| UC-06 Asignar visibilidad por rol | `Admin` | `ACT-PUB-03`, `ACT-PUB-06` | `SEQ-PUB-01`, `SEQ-PUB-05` | Roles lectores vinculados al documento |
| UC-07 Actualizar versión del documento | `Admin` | `ACT-ACC-01..03`, `ACT-PUB-01`, `ACT-UPD-01..06` | `SEQ-ACC-01..03`, `SEQ-UPD-01..06` | Versión actualizada y confirmada |
| UC-08 Archivar documento | `Admin` | `ACT-ACC-01..03`, `ACT-PUB-01`, `ACT-ARC-01..04` | `SEQ-ACC-01..03`, `SEQ-ARC-01..04` | Documento archivado y excluido de la consulta activa |
| UC-09 Verificar identidad, tenant y roles | Control de acceso existente del HIS | `ACT-ACC-01..03`, `ACT-EX-01..03` | `SEQ-ACC-01..03`, `SEQ-EX-01..03` | Acceso autorizado o rechazo seguro |
| UC-10 Validar información de publicación | Módulo ASII-24, invocado por acciones de `Admin` | `ACT-PUB-04`, `ACT-UPD-03`, `ACT-EX-04` | `SEQ-PUB-03`, `SEQ-UPD-03`, `SEQ-EX-04` | Datos válidos o corrección solicitada |

Las identidades de excepción preservan la trazabilidad de autenticación inválida, tenant no coincidente, rol insuficiente, datos inválidos, documento no disponible y fallos de almacenamiento o persistencia.

## 9. Verificación de consistencia entre diagramas

- Los actores de consulta y administración coinciden con el catálogo aprobado de Semana 1.
- `UC-09` conecta con el control de acceso existente del HIS en ambos diagramas.
- Las actividades de publicación, actualización, archivado y consulta se corresponden con los mensajes de secuencia del mismo flujo.
- Las excepciones de actividad y secuencia cubren el mismo conjunto de fallos conceptuales.

## 10. Límites de esta evidencia

- No define endpoints HTTP.
- No define contratos OpenAPI ni colecciones Postman.
- No crea esquema de base de datos ni código de implementación.
- No sustituye las capacidades base existentes del HIS.
