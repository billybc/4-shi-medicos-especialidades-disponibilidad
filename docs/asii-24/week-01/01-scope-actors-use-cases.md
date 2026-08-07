# ASII-24 — Alcance, actores y casos de uso

## 1. Identificación de la actividad

| Campo | Valor |
|---|---|
| Estudiante | ALBINO SEBASTIAN ROSALES RUANO |
| GitHub | `codsebas` |
| Módulo oficial | Contratos API: OpenAPI/Postman y documentación técnica |
| Actividad adaptada | Centro de documentación y manuales por rol |
| Semana | 1 |
| Tipo de evidencia | Análisis, UML y trazabilidad inicial |

## 2. Contexto del repositorio

| Elemento | Estado verificado | Uso en ASII-24 |
|---|---|---|
| JWT | Implementado en el backend base | Controla el acceso autenticado a la evidencia propuesta |
| `X-Tenant-ID` | Requerido por middleware de tenant | Delimita el alcance al tenant activo |
| RBAC | Roles semilla con Spatie Permission | Determina lectores y administrador |
| API actual | Solo autenticación bajo `/api/v1` | Sirve como base existente, sin módulo documental propio |
| Centro de documentación | No implementado | Es el diseño propuesto para la Semana 1 |

## 3. Problema que se modela

El HIS necesita un centro documental que permita publicar, consultar y controlar manuales o documentos técnicos según el rol del usuario autenticado dentro del tenant activo. La necesidad académica se centra en separar con claridad lo que ya existe en el repositorio de lo que se propone para ASII-24.

## 4. Objetivo del módulo adaptado

Modelar un centro de documentación y manuales por rol que permita a `Admin` publicar y administrar documentos, y a los roles lectores consultar información autorizada sin cruzar límites de tenant.

## 5. Límite del sistema

El límite funcional de esta evidencia incluye la consulta, búsqueda, visualización, descarga, publicación, asignación de visibilidad por rol, actualización de versión y archivado de documentación dentro del tenant activo.

## 6. Alcance incluido

- Consulta de documentación disponible para usuarios autenticados.
- Búsqueda y filtrado por criterios conceptuales de publicación.
- Visualización y descarga de documentos o manuales autorizados.
- Publicación y administración documental por `Admin`.
- Verificación conceptual de identidad, tenant y roles.
- Trazabilidad entre casos de uso, actividad y secuencia en la evidencia de la semana.

## 7. Fuera de alcance para la Semana 1

- Implementación de endpoints.
- Contratos OpenAPI o colecciones Postman.
- Código de controladores, servicios o persistencia.
- Migraciones, tablas o modelo de base de datos.
- Pruebas automatizadas.
- Prototipos UI.

## 8. Actores

| Actor | Tipo | Estado | Responsabilidad |
|---|---|---|---|
| Usuario autenticado del HIS | Primario general | Verificado como concepto de diseño | Consultar, buscar, visualizar o descargar documentación autorizada |
| `Admin` | Primario administrativo | Propuesto para ASII-24 | Publicar, asignar visibilidad por rol, actualizar versiones y archivar documentos |
| `Médico` | Primario lector | Verificado por roles semilla | Consultar documentación permitida para su rol |
| `Enfermera` | Primario lector | Verificado por roles semilla | Consultar documentación permitida para su rol |
| Técnico de laboratorio (`TecnicoLab`) | Primario lector | Verificado por roles semilla | Consultar documentación permitida para su rol |
| `Recepcionista` | Primario lector | Verificado por roles semilla | Consultar documentación permitida para su rol |
| Control de acceso existente del HIS (JWT, tenant y RBAC) | Actor de soporte | Verificado como capacidad base | Validar identidad, tenant y roles antes de permitir operaciones |

## 9. Reglas de negocio propuestas

1. Solo `Admin` puede publicar documentación o manuales.
2. Solo `Admin` puede asignar roles lectores, actualizar una versión y archivar un documento.
3. Un usuario no administrador solo puede consultar documentos cuando su JWT es válido, el tenant coincide, el documento pertenece al tenant activo, el documento está disponible y existe intersección entre sus roles y los roles lectores autorizados.
4. El acceso entre tenants debe rechazarse siempre.
5. `Admin` hereda la capacidad general de consulta autenticada y además accede a los metadatos necesarios para administrar documentos dentro del tenant activo.

## 10. Procesos principales

- Publicación documental por parte de `Admin`.
- Configuración de visibilidad por rol.
- Consulta general de documentación disponible.
- Búsqueda y filtrado de documentos.
- Visualización o descarga de un documento autorizado.
- Actualización de versión y archivado por `Admin`.

## 11. Catálogo de casos de uso

| ID | Caso de uso | Actor principal |
|---|---|---|
| UC-01 | Consultar documentación disponible | Usuario autenticado del HIS |
| UC-02 | Buscar y filtrar documentación | Usuario autenticado del HIS |
| UC-03 | Visualizar documento o manual | Usuario autenticado del HIS |
| UC-04 | Descargar documento o manual | Usuario autenticado del HIS |
| UC-05 | Publicar documento o manual | `Admin` |
| UC-06 | Asignar visibilidad por rol | `Admin` |
| UC-07 | Actualizar versión del documento | `Admin` |
| UC-08 | Archivar documento | `Admin` |
| UC-09 | Verificar identidad, tenant y roles | Control de acceso existente del HIS |
| UC-10 | Validar información de publicación | Módulo ASII-24, invocado por acciones de `Admin` |

## 12. Descripción breve de los casos de uso

### UC-01 — Consultar documentación disponible
- Nombre: Consultar documentación disponible
- Objetivo: Mostrar al usuario autenticado la documentación autorizada dentro del tenant activo.
- Actor principal: Usuario autenticado del HIS
- Precondición conceptual: Sesión válida, tenant identificado y contexto de roles disponible.
- Flujo principal resumido: El sistema verifica el contexto, consulta la disponibilidad y presenta la lista autorizada.
- Resultado: El usuario visualiza la documentación que puede consultar.
- Excepción principal: Rechazo por autenticación inválida, tenant incorrecto o falta de autorización.

### UC-02 — Buscar y filtrar documentación
- Nombre: Buscar y filtrar documentación
- Objetivo: Permitir localizar documentos por criterios de consulta.
- Actor principal: Usuario autenticado del HIS
- Precondición conceptual: El usuario tiene acceso válido al centro documental.
- Flujo principal resumido: El usuario aplica filtros y el sistema restringe los resultados a lo permitido por tenant y roles.
- Resultado: Se muestran resultados filtrados o un estado vacío.
- Excepción principal: No hay resultados accesibles o el acceso queda denegado.

### UC-03 — Visualizar documento o manual
- Nombre: Visualizar documento o manual
- Objetivo: Permitir abrir un documento autorizado en pantalla.
- Actor principal: Usuario autenticado del HIS
- Precondición conceptual: El documento existe, pertenece al tenant y no está archivado.
- Flujo principal resumido: El usuario selecciona un documento y el sistema valida acceso antes de mostrar el contenido.
- Resultado: El documento se visualiza de forma autorizada.
- Excepción principal: Documento inexistente, archivado o no autorizado.

### UC-04 — Descargar documento o manual
- Nombre: Descargar documento o manual
- Objetivo: Permitir obtener una copia autorizada del documento.
- Actor principal: Usuario autenticado del HIS
- Precondición conceptual: El documento es descargable y el acceso ha sido validado.
- Flujo principal resumido: El usuario solicita descarga y el sistema entrega el archivo permitido.
- Resultado: Se descarga el documento autorizado.
- Excepción principal: El archivo no está disponible o el usuario no tiene permiso.

### UC-05 — Publicar documento o manual
- Nombre: Publicar documento o manual
- Objetivo: Registrar un nuevo documento dentro del tenant activo.
- Actor principal: `Admin`
- Precondición conceptual: El actor es `Admin` y dispone de contexto válido.
- Flujo principal resumido: Se ingresan metadatos, roles lectores y archivo o referencia; luego se valida y registra la publicación.
- Resultado: El documento queda publicado con su versión inicial.
- Excepción principal: Fallo de validación, almacenamiento o persistencia.

### UC-06 — Asignar visibilidad por rol
- Nombre: Asignar visibilidad por rol
- Objetivo: Definir qué roles pueden consultar un documento.
- Actor principal: `Admin`
- Precondición conceptual: Existe un documento del tenant activo en proceso de administración.
- Flujo principal resumido: El `Admin` selecciona los roles autorizados y el sistema conserva esa relación conceptual.
- Resultado: La visibilidad queda definida para lectores permitidos.
- Excepción principal: Selección de roles inválida o incompleta.

### UC-07 — Actualizar versión del documento
- Nombre: Actualizar versión del documento
- Objetivo: Registrar una nueva versión documental.
- Actor principal: `Admin`
- Precondición conceptual: El documento pertenece al tenant activo y el actor tiene permisos administrativos.
- Flujo principal resumido: El `Admin` selecciona el documento, envía cambios permitidos y el sistema valida y registra la nueva versión.
- Resultado: La versión documental queda actualizada.
- Excepción principal: Validación fallida o persistencia incompleta.

### UC-08 — Archivar documento
- Nombre: Archivar documento
- Objetivo: Retirar un documento de la consulta activa sin eliminar su historial.
- Actor principal: `Admin`
- Precondición conceptual: El documento existe y pertenece al tenant activo.
- Flujo principal resumido: El `Admin` confirma el archivo y el sistema marca el documento como archivado.
- Resultado: El documento deja de aparecer como disponible.
- Excepción principal: La operación se cancela o el documento no puede archivarse.

### UC-09 — Verificar identidad, tenant y roles
- Nombre: Verificar identidad, tenant y roles
- Objetivo: Confirmar que el contexto de acceso es válido antes de cualquier operación.
- Actor principal: Control de acceso existente del HIS
- Precondición conceptual: Se recibió JWT y cabecera `X-Tenant-ID`.
- Flujo principal resumido: El sistema valida identidad, tenant y roles antes de liberar el acceso.
- Resultado: Se devuelve contexto autorizado o rechazo.
- Excepción principal: Autenticación inválida, tenant inexistente o no coincidente.

### UC-10 — Validar información de publicación
- Nombre: Validar información de publicación
- Objetivo: Confirmar que los datos conceptuales de publicación son correctos antes de registrar el documento.
- Actor principal: Módulo ASII-24, invocado por acciones de `Admin`
- Precondición conceptual: El `Admin` capturó metadatos y selección de roles lectores.
- Flujo principal resumido: El sistema revisa coherencia de datos, archivo o referencia y reglas de visibilidad.
- Resultado: La información queda validada o se reportan errores para corrección.
- Excepción principal: Datos incompletos, inconsistentes o no válidos.

## 13. Diagrama UML de casos de uso

![Diagrama UML de casos de uso del módulo ASII-24](diagrams/use-case-diagram.svg)
## 14. Decisiones y supuestos de diseño

- Se mantiene la distinción entre capacidades base ya verificadas del HIS y el centro documental propuesto para ASII-24.
- `Admin` es la única figura administrativa aprobada para la Semana 1.
- `TecnicoLab` se presenta como `Técnico de laboratorio` en lenguaje humano, sin cambiar el nombre técnico del rol.
- La evidencia de esta semana modela comportamiento conceptual; no define contratos API ni estructura de datos.
