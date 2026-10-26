# Planificación y seguimiento con ProofHub

[Volver al proyecto](../README.md) · [Arquitectura y estado de validación](arquitectura.md)

## 1. Uso documentado

El Word contiene dos capturas del tablero ProofHub del proyecto. Muestran tareas repartidas entre integrantes del equipo, fechas previstas y estados de seguimiento. El README asigna a Elian la parte de ProofHub y diagrama.

Esta sección describe esas vistas conservadas en el documento. No se ha accedido al tablero en línea ni se considera que sus estados sigan siendo los actuales.

## 2. Organización de las tareas

Las columnas visibles separan trabajo pendiente, asignado, en curso, bloqueado y pendiente de revisión. Entre las tarjetas se identifican:

- Configuración de R-N02 y diagrama de arquitectura.
- Preparación de las VMs y servicios DNS y DHCP.
- Creación de W-N02, Apache, PHP y SSH.
- Preparación de la base de datos y carga del CSV.
- Servidor FTP F-N02.
- Clientes Linux y Windows.
- Aplicación web con login y listado.
- Conexión SSH de W-N02 con GitHub y documentación en Markdown.

![Primera vista del tablero del proyecto](../imagenes/ProofHub/01-tablero-proyecto.png)

*Figura 1. Vista de tareas por estado, con responsables y fechas previstas. El diagrama de arquitectura figura en la columna de revisión.*

![Segunda vista del seguimiento del proyecto](../imagenes/ProofHub/02-tablero-seguimiento.png)

*Figura 2. Otra vista del tablero, con tareas de infraestructura, clientes y aplicación.*

## 3. Relación con la entrega técnica

Una tarea situada en revisión requiere comprobar sus resultados antes de considerarla cerrada. Las capturas técnicas más recientes del Word pueden acreditar avances que todavía no se reflejen en estas vistas del tablero.

Por ello, el estado documental se establece a partir de los resultados observados: login FTP correcto, aplicación web visible y parámetros DHCP de los clientes, entre otros. No se da por completada una tarea solo por su posición en ProofHub, ni por finalizado el proyecto porque exista un tablero.

## 4. Seguimiento pendiente

Para cerrar la planificación conviene asociar a cada tarea su documento, su configuración y una prueba de aceptación. Queda pendiente una vista actualizada que muestre la revisión final de las tareas y permita contrastarla con las evidencias del repositorio.
