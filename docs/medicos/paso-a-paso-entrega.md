# Paso a paso de la entrega

Este archivo indica qué hacer, dónde hacerlo y qué evidencia guardar.

## Paso 1 - Revisar el repositorio

Desde la carpeta raíz del repositorio:

```powershell
git status
git branch --show-current
git worktree list
```

La rama actual es `feature/week-03-04-medicos`. Como ya contiene el avance de semanas 3 y 4, puedes continuar en ella si el docente acepta esa convención. No borres los cambios existentes de `jsconfig.json` ni `package-lock.json`.

## Paso 2 - Revisar la evidencia creada

Abre estos archivos en este orden:

1. `docs/medicos/razonamiento-arquitectonico.md`
2. `docs/medicos/diagramas/01-componentes.mmd`
3. `docs/medicos/diagramas/02-secuencia.mmd`
4. `docs/medicos/semana-05-api-y-microservicio.md`
5. `docs/medicos/semana-06-defensa-arquitectura.md`

Debes poder explicar el recorrido:

```text
Cliente -> API -> Controller -> Service -> Repository -> Base de datos
```

## Paso 3 - Crear el issue en GitHub

En el repositorio público, crea un issue con este título:

```text
ASII-04 - API REST y evaluacion de microservicio para medicos
```

Copia como contenido:

```markdown
## Objetivo
Evolucionar el flujo de medicos, especialidades y disponibilidad hacia cliente-servidor mediante API REST y evaluar una frontera de microservicio.

## Alcance
- Contrato API de medicos, especialidades y disponibilidad.
- Seguridad JWT, permisos y tenant.
- Propiedad de datos, resiliencia, observabilidad y consistencia.
- Evaluacion de Availability Service.
- Presentacion de semana 6.

## Criterios de aceptacion
- [ ] Contrato API documentado.
- [ ] Diagrama cliente-servidor actualizado.
- [ ] Propiedad de datos definida.
- [ ] Conflicto de horarios documentado con HTTP 409.
- [ ] Matriz decision-evidencia completada.
- [ ] Rama y Pull Request enlazados.
```

Guarda el número del issue, por ejemplo `#15`.

## Paso 4 - Confirmar cambios y crear commit

Ejecuta:

```powershell
git diff --check
git status --short
git add docs/medicos
git commit -m "docs: agregar evidencia de semanas 5 y 6 para medicos"
```

No agregues `jsconfig.json` ni `package-lock.json` a este commit si esos cambios no forman parte de esta tarea.

## Paso 5 - Publicar la rama

Si la rama ya está vinculada al repositorio remoto:

```powershell
git push
```

Si Git solicita configurar upstream:

```powershell
git push -u origin feature/week-03-04-medicos
```

## Paso 6 - Crear el Pull Request

En GitHub crea un PR hacia la rama base indicada por el docente, normalmente `develop`.

Título sugerido:

```text
ASII-04: evidencia de API REST y defensa arquitectónica
```

Descripción sugerida:

```markdown
## Resumen
Se documenta la evolución cliente-servidor del módulo de médicos, especialidades y disponibilidad.

## Evidencia
- Contrato API: docs/medicos/semana-05-api-y-microservicio.md
- Defensa: docs/medicos/semana-06-defensa-arquitectura.md
- Diagramas: docs/medicos/diagramas/
- Razonamiento: docs/medicos/razonamiento-arquitectonico.md

## Decisión arquitectónica
Se mantiene un monolito modular con API REST y se evalúa Availability Service como frontera futura. La extracción queda condicionada a métricas de carga, consumidores e independencia de despliegue.

## Validación
- [x] git diff --check
- [ ] Captura de ejecución local
- [ ] Revisión de enlaces
```

Copia el enlace del PR.

## Paso 7 - Preparar la evidencia visual

Debes guardar como mínimo:

- Captura del repositorio público.
- Captura del issue.
- Captura de la rama y del PR.
- Captura del tablero de médicos funcionando, si PHP está disponible.
- Captura o exportación de las 8 diapositivas.

La evidencia de ejecución anterior indica que en este equipo PHP no estaba disponible. Si continúa igual, ejecuta la aplicación en una máquina con PHP 8.2 y guarda la captura allí.

## Paso 8 - Crear la presentación

Usa `docs/medicos/semana-06-defensa-arquitectura.md` como guion. La presentación debe tener entre 6 y 8 diapositivas.

No llenes las diapositivas con párrafos. Usa el diagrama, una tabla corta y un ejemplo JSON. La explicación detallada queda en el repositorio.

## Paso 9 - Actualizar Project Odoo

En la tarjeta o tarea de tu módulo registra:

```text
Estudiante: CARDONA LÓPEZ, BILLY EDUARDO
Email: bcardonal1@miumg.edu.gt
Módulo: Médicos, especialidades y disponibilidad
Repositorio: [URL pública]
Issue: [URL del issue]
Pull Request: [URL del PR]
Evidencia semana 5: docs/medicos/semana-05-api-y-microservicio.md
Evidencia semana 6: docs/medicos/semana-06-defensa-arquitectura.md
Estado: Entregado para revisión
```

## Paso 10 - Ensayar la defensa

Responde estas cuatro preguntas:

1. ¿Por qué API REST? Porque separa cliente y servidor y permite que otros módulos consuman disponibilidad.
2. ¿Por qué no microservicio todavía? Porque el costo distribuido no se justifica sin métricas.
3. ¿Dónde se valida el conflicto? En el servidor, dentro del servicio de aplicación y el Repository.
4. ¿Qué cambia si cambia SQLite? Solo el adaptador Repository; el caso de uso conserva su contrato.
