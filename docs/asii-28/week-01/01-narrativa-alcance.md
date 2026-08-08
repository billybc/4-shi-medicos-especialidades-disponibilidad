# ASII-28 - Semana 1: Narrativa y alcance

## 1. Contexto del problema

El Sistema Hospitalario Integrado (HIS) reúne procesos administrativos y clínicos que
dependen unos de otros. Un error en autenticación, aislamiento por hospital, permisos
o manejo de datos clínicos puede afectar varios módulos y comprometer la operación. Por
eso no basta con que cada desarrollo compile o funcione solamente en su escenario
principal: se necesita una verificación funcional repetible que demuestre el
comportamiento esperado y detecte regresiones durante la integración.

El módulo **ASII-28 - QA funcional, pruebas manuales y matriz de regresión** establece
ese proceso transversal. Su propósito es convertir los requisitos y criterios de
aceptación de los módulos del HIS en casos de prueba manuales trazables, ejecutarlos en
un ambiente identificado, conservar evidencia, comunicar defectos reproducibles y
mantener una matriz de regresión basada en riesgo.

## 2. Alcance del módulo

El módulo cubre el ciclo manual de calidad desde la selección de funcionalidades que se
van a probar hasta la comunicación del resultado. Incluye planificación, diseño de
casos, preparación de datos, ejecución, registro de evidencias, reporte de defectos,
re-prueba de correcciones y regresión de los flujos críticos.

Cada resultado debe indicar como mínimo la versión o commit evaluado, ambiente, tenant,
rol, datos utilizados, resultado esperado, resultado obtenido, estado y evidencia. La
matriz crecerá conforme los módulos clínicos se integren en `develop`; los casos de un
módulo todavía no disponible se identificarán como bloqueados o no ejecutados, nunca
como aprobados sin evidencia.

## 3. Valor dentro del HIS

El módulo aporta una visión transversal de la calidad y permite responder:

- qué requisito o flujo fue probado;
- con qué rol, tenant, datos y versión se ejecutó;
- cuál fue el resultado y dónde está la evidencia;
- qué defecto impidió la aprobación;
- qué corrección fue verificada;
- qué pruebas deben repetirse antes de integrar o liberar cambios.

Se priorizan autenticación, autorización, aislamiento entre tenants, integridad de datos
clínicos y flujos que conectan varios módulos. Mientras solo esté disponible la API base
de autenticación, esta será la primera línea base funcional de la regresión.

## 4. Delimitación con otros módulos

ASII-28 es responsable de **QA funcional manual y matriz de regresión**. No implementa
las funcionalidades clínicas evaluadas ni corrige sus defectos; esas tareas pertenecen
al responsable de cada módulo.

La automatización E2E, la integración continua y la guía de despliegue final pertenecen
a **ASII-25**. ASII-28 puede compartir escenarios estables y resultados con ASII-25,
pero conserva la responsabilidad sobre el inventario manual, la evidencia funcional y
la regresión basada en riesgo.

## 5. Resultado esperado

Al finalizar el proyecto debe existir un proceso de QA reproducible y una matriz de
regresión actualizada que permitan conocer el estado funcional del HIS. Cada conclusión
de `Aprobado`, `Fallido`, `Bloqueado` o `No ejecutado` deberá estar respaldada por un
caso de prueba y su registro de ejecución.
