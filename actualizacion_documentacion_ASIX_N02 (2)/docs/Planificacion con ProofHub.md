# Planificación y seguimiento del proyecto con ProofHub

[Volver al proyecto](../README.md) · [Arquitectura](arquitectura.md) · [Manual de usuario y administración](<Manual de usuario y administracion.md>)

## 1. Cómo organizamos el trabajo

Utilizamos **ProofHub** para repartir el trabajo de la infraestructura N02, seguir las tareas y comprobar qué quedaba pendiente. En el tablero se agruparon actividades relacionadas con el router, las máquinas virtuales, DNS y DHCP, la base de datos, el servidor web, el FTP, los equipos cliente y la documentación.

Para explicar mejor el proceso, presentamos el proyecto en **tres sprints**. Esta división resume el orden lógico del trabajo: primero preparar la infraestructura, después instalar los servicios y, finalmente, integrarlos y comprobarlos. Las tres imágenes guardadas en el repositorio representan tres vistas del seguimiento. **En las dos primeras capturas la columna aparece literalmente como `Assignades spring`, sin número; en la tercera hay tareas cuyo nombre empieza por `SP3`.** No tenemos un histórico exportado de ProofHub que permita atribuir una fecha exacta de inicio o cierre a los sprints 1 y 2, por lo que evitamos inventar calendarios, porcentajes o estados que las capturas no demuestran. La división que sigue es una **organización retrospectiva de la memoria**, no una afirmación de que ProofHub mostrase tres sprints ya creados.

| Sprint | Objetivo | Resultado que buscamos |
| --- | --- | --- |
| **Sprint 1. Planificación e infraestructura base** | Diseñar las redes, distribuir responsabilidades y preparar el router y las VMs. | Equipos y segmentos identificados, router encaminando tráfico y arquitectura documentada. |
| **Sprint 2. Despliegue de servicios** | Instalar y configurar los servidores de DNS/DHCP, web, FTP y base de datos. | Servicios disponibles y primeras pruebas técnicas de funcionamiento. |
| **Sprint 3. Integración, pruebas y entrega** | Conectar clientes, verificar la aplicación, revisar evidencias y entregar la documentación. | Pruebas entre componentes, repositorio ordenado y pendientes identificados. |

### Equipo y reparto de tareas

Este es el reparto inicial recogido en el [README](../README.md). Sirve de referencia para entender los responsables, pero no significa que una tarea concreta se completara exclusivamente por una persona.

| Integrante | Responsabilidad inicial |
| --- | --- |
| **Elian** | Router, planificación con ProofHub y diagrama de arquitectura. |
| **Daniel** | Repositorio GitHub y servicios DNS/DHCP. |
| **Faiver** | Servidor web, SSH, clientes y aplicación PHP. |
| **Elias** | Base de datos y servidor FTP. |

El tablero permite identificar tareas pendientes, asignadas, en curso, bloqueadas y pendientes de revisión. Estas columnas sirven para organizar el trabajo, pero el estado que aparece en una captura corresponde **al momento en que se hizo**, no necesariamente al estado actual de cada servicio.

## 2. Sprint 1 — Planificación e infraestructura base

**Objetivo.** Tener una propuesta de red clara y una base funcional sobre la que después pudieran trabajar los responsables de cada servicio.

**Trabajo incluido en esta fase:**

1. Definir los roles del equipo y registrar las tareas en ProofHub.
2. Preparar la estructura del repositorio y separar documentación, configuración y capturas.
3. Diseñar las tres redes de Isard: `Default` (exterior), `ITB 2` (DMZ, `192.168.10.0/24`) e `ITB 1` (Intranet, `192.168.20.0/24`).
4. Preparar las máquinas virtuales según el esquema y definir nombres e IPs.
5. Configurar el router R-N02 con tres interfaces, reenvío IPv4 y NAT para la salida a Internet.

**Evidencias técnicas relacionadas:** [Arquitectura](arquitectura.md) y [Router R-N02](router.md). Las capturas del router muestran la configuración de IP, `ip_forward=1`, la regla NAT y pruebas desde la DMZ hacia el gateway y una IP exterior.

![Primera vista del tablero de ProofHub](../imagenes/ProofHub/01-tablero-proyecto.png)

*Figura 1. Primera vista de ProofHub: se ven las columnas `Pendents backlog (To Do)`, `Assignades spring`, `Bloquejades (Block)` y `Pendents de Revisió (Review)`. La configuración de R-N02 y el diagrama de arquitectura aparecen pendientes de revisión, mientras la base de datos figura en `Assignades spring`. Hay tareas con fechas previstas del 19 de octubre y del 4 de noviembre, sin que esta captura confirme el periodo completo de un sprint.*

En el backlog de esta primera imagen se pueden identificar tareas como **crear el repositorio GitHub**, **montar ProofHub**, **crear W-N02 y B-N02**, **crear la VM de DNS/DHCP**, **instalar Apache/PHP/SSH**, **configurar DNS/DHCP** y **preparar el FTP**. Verlas en el backlog significa que estaban registradas allí en ese momento, no que ya estuvieran terminadas.

**Cierre de la fase.** La documentación técnica acredita que el router se configuró y que desde W-N02 se llegó tanto a la puerta de enlace `192.168.10.1` como a `8.8.8.8`. Eso no demuestra por sí mismo que todas las reglas de seguridad entre la DMZ y la Intranet estén cerradas; esa revisión sigue pendiente.

## 3. Sprint 2 — Despliegue y configuración de servicios

**Objetivo.** Incorporar los servicios que necesitan los clientes y la aplicación, dejando constancia de su configuración y sus primeras pruebas.

**Trabajo incluido en esta fase:**

1. Configurar DNS-N02 (`192.168.20.10`) con `dnsmasq` para DNS y DHCP, entregando el rango `192.168.20.100-150`.
2. Instalar Apache, PHP y el conector MySQL en W-N02 (`192.168.10.10`) y preparar la aplicación.
3. Preparar la base de datos de equipamientos en B-N02 (`192.168.20.20`, según el diseño), a partir del CSV trabajado por el equipo.
4. Instalar y configurar `vsftpd` en F-N02 (`192.168.10.11`) para permitir acceso a usuarios locales.
5. Preparar los accesos de administración y documentar los archivos de configuración disponibles sin presentar fragmentos como instalaciones completas.

**Evidencias técnicas relacionadas:** [DNS/DHCP](<DNS y DHCP.md>), [Web y aplicación](<Servidor web, SSH y aplicación de prueba.md>), [Base de datos](<Base de datos.md>) y [FTP](<Servidor FTP.md>).

![Segunda vista del seguimiento en ProofHub](../imagenes/ProofHub/02-tablero-seguimiento.png)

*Figura 2. Segunda vista del tablero: aparecen las columnas `Pendents backlog`, `Assignades spring`, `En curs (Doing)`, `Bloquejades (Block)` y `Pendents de Revisió (Review)`. Se ven actividades de FTP, Apache/PHP/SSH, base de datos, DNS/DHCP y preparación de VMs, además de clientes y aplicación web. El tablero muestra avatares con iniciales como `ML`, `SE`, `SF` y `RD`; la captura por sí sola no permite identificar de manera inequívoca a todos sus titulares.*

Esta segunda imagen permite observar que el trabajo se estaba siguiendo desde **varios estados**, no únicamente desde una lista de cosas por hacer. También aparecen tareas de **los dos clientes**, **el login y listado de la aplicación web** y **la clave SSH de W-N02 con GitHub y la documentación**. Por eso se entiende como una vista de seguimiento durante el despliegue y la integración, sin convertirla en un listado de resultados ya comprobados.

**Resultado contrastado con el repositorio.** Hay capturas de `dnsmasq`, Apache y `vsftpd` activos, de consultas DNS y de conectividad. En el caso de la base de datos, el historial conserva intentos de preparación del CSV y la aplicación muestra registros en el navegador; **no** se conserva todavía un volcado SQL ni la configuración MySQL completa. El acceso SSH final en W-N02 tampoco está comprobado con una evidencia propia.

## 4. Sprint 3 — Integración, validación y entrega

**Objetivo.** Confirmar que los componentes se comunican entre sí y cerrar la entrega con documentación útil para quien deba utilizar o administrar el sistema.

**Trabajo incluido en esta fase:**

1. Configurar PCL-N02 y PCW-N02 para obtener la red por DHCP desde DNS-N02.
2. Probar la resolución de nombres internos y externos, las rutas de los clientes y el acceso entre Intranet y DMZ.
3. Comprobar el acceso al FTP desde Linux.
4. Abrir la aplicación desde un navegador, iniciar sesión y mostrar el listado de equipamientos procedente de MySQL.
5. Revisar el repositorio, relacionar las capturas con su explicación y preparar el manual de uso, operación, copias y recuperación.

**Evidencias técnicas relacionadas:** [Clientes](Clientes.md), [Web y aplicación](<Servidor web, SSH y aplicación de prueba.md>), [FTP](<Servidor FTP.md>) y [Manual de usuario y administración](<Manual de usuario y administracion.md>).

![Tercera captura del seguimiento de ProofHub](<../imagenes/ProofHub/Captura de pantalla de 2026-11-23 15-17-48.png>)

*Figura 3. Captura del 23 de noviembre de 2026 a las 15:17, según el nombre del archivo. Se ven tres tareas de `SP3` en **`En curs (Doing)`**: revisar la documentación, documentarlo todo en Markdown y comprobar que todo funcione. En **`Finalitzades (Done)`** aparecen, entre otras, la creación del repositorio, la base de datos, la VM B-N02, DNS/DHCP y la puesta en marcha de ProofHub.*

**Lectura de esta captura:** ProofHub identifica explícitamente el trabajo del **sprint 3** mediante el prefijo `SP3`, pero sus tres tareas visibles aún están en curso en el momento de la imagen. Que otras tarjetas estén en `Done` refleja el estado del tablero; las verificaciones técnicas concretas se deben justificar por separado. Tampoco se muestran aquí sprints 1 y 2 etiquetados de forma literal.

**Resultados que sí podemos explicar con las pruebas guardadas:**

| Comprobación | Evidencia en el repositorio | Estado documental |
| --- | --- | --- |
| Salida a Internet desde la DMZ | Ping desde W-N02 al router y a `8.8.8.8`. | **Comprobado en captura.** |
| DNS y DHCP | Consultas `nslookup`, estado de `dnsmasq` e IPs recibidas por los clientes. | **Comprobado en capturas.** |
| Servidor web y PHP | Apache activo, formulario y tabla visible tras el inicio de sesión. | **Comprobado en capturas.** |
| Acceso al FTP | Respuesta `230 Login successful` desde PCL-N02. | **Comprobado el login**, no una transferencia completa. |
| Importación reproducible de MySQL | Historial de trabajo y resultado visible desde PHP. | **Pendiente**: esquema, carga y permisos efectivos. |
| Acceso SSH a W-N02 | Servicio previsto en el alcance. | **Pendiente** de prueba remota final. |
| Copia y restauración | Procedimiento propuesto en el manual. | **Pendiente** de implementación y ensayo. |

## 5. Cómo leer las capturas y qué queda por revisar

Las imágenes de ProofHub muestran cómo usamos un tablero para distribuir trabajo y hacer seguimiento. No sustituyen las evidencias técnicas de cada máquina: para considerar una tarea realizada es importante poder enseñar también el comando, el estado del servicio, la configuración o una prueba desde un cliente.

**Para dejar también ProofHub dividido en tres sprints reales**, habría que revisar allí las tareas con la persona responsable y etiquetar o agrupar cada una como `SP1`, `SP2` o `SP3`, de forma coherente con las secciones anteriores. Conviene corregir el nombre de la columna `Assignades spring` a `Assignades a l'sprint` (o `Asignadas al sprint`) y guardar una captura de cada agrupación. Esta modificación del tablero **no se ha realizado desde el repositorio**.

**Al revisar el tablero para cerrar el proyecto**, conviene comprobar que cada tarea tenga una persona responsable, una descripción concreta, su estado actualizado y un enlace a la evidencia técnica correspondiente. Si una tarea está terminada en ProofHub pero su prueba aún no está en GitHub, hay que adjuntarla o dejar claro que la validación está pendiente. También hay que confirmar las fechas reales de los tres sprints en el historial de ProofHub antes de añadirlas a esta memoria.

## 6. Conclusión

ProofHub nos ha servido para organizar el trabajo del equipo y seguir una infraestructura con varios servidores y dependencias. La división en tres sprints permite explicar mejor la evolución: **preparación de la base**, **despliegue de servicios** y **pruebas con documentación final**. El repositorio deja separadas las comprobaciones que se ven en las capturas y las partes que todavía necesitan una prueba adicional para considerar la entrega completamente validada.
