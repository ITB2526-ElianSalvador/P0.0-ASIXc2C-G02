# Control de versiones con Git

[Volver al proyecto](../README.md) · [Ficheros de configuración](<Ficheros de configuración.md>)

## 1. Repositorio y revisión de referencia

El equipo utiliza [P0.0-ASIXc2C-G02](https://github.com/ITB2526-ElianSalvador/P0.0-ASIXc2C-G02) para conservar la memoria, configuraciones y evidencias. La preparación de esta actualización toma como referencia la rama `main`, revisada el 26 de octubre de 2026, en el commit `41b136fe5ee56eb209b4b960d762ff5287f759a7`.

El responsable asignado al repositorio es Daniel. El historial acredita cambios publicados por integrantes del equipo; no se interpreta como prueba de un flujo concreto de terminal, revisiones por pull request o autenticación SSH.

## 2. Organización de la entrega

| Ruta | Contenido | Criterio |
| --- | --- | --- |
| `README.md` | Presentación, inventario e índice. | Entrada al proyecto. |
| `docs/` | Procedimientos, explicaciones y resultados. | Cada afirmación se vincula a su evidencia o se marca como pendiente. |
| `config/` | Archivos, fragmentos y evidencias de texto. | Cada inventario indica procedencia y si el contenido está completo. |
| `imagenes/` | Capturas separadas por equipo o componente. | Enlaces relativos desde los documentos Markdown. |

Los nombres de los archivos se respetan con su capitalización real. Por ejemplo, el documento de arquitectura es `docs/arquitectura.md`; utilizar `Arquitectura.md` rompe el enlace en un entorno que distingue mayúsculas y minúsculas.

Los archivos de una actualización deben integrarse en estas carpetas de la raíz. Una carpeta de distribución llamada `archivos/` contiene los archivos que se deben copiar, pero no forma parte de la estructura del repositorio.

## 3. Evidencia de uso del repositorio

Entre los commits consultados figuran:

| Commit | Fecha local | Mensaje observado |
| --- | --- | --- |
| [4a27421](https://github.com/ITB2526-ElianSalvador/P0.0-ASIXc2C-G02/commit/4a27421eb07be20e813fdf4487ce4fc30eb6513b) | 22 de octubre de 2026 | `Update Base de datos.md` |
| [41b136f](https://github.com/ITB2526-ElianSalvador/P0.0-ASIXc2C-G02/commit/41b136fe5ee56eb209b4b960d762ff5287f759a7) | 26 de octubre de 2026 | `Add files via upload` |

La revisión de referencia ya contiene los archivos y las capturas de la parte Router en su ubicación correspondiente. Estos hechos permiten documentar el uso de GitHub como repositorio compartido. Para acreditar una conexión SSH desde W-N02 todavía hace falta su propia prueba.

## 4. Procedimiento propuesto para futuras aportaciones

El siguiente flujo es una guía de trabajo; no se presenta como un historial de órdenes ya ejecutadas por el equipo.

1. Actualizar una copia de `main` que no tenga cambios locales pendientes.
2. Crear una rama para una tarea concreta.
3. Añadir texto, configuración e imágenes en las rutas previstas.
4. Revisar las diferencias y la vista renderizada del Markdown.
5. Publicar la rama y abrir una pull request para revisión.

Antes de guardar un cambio, revisar:

```bash
git status --short
git diff
git diff --cached
```

`git diff` muestra los cambios aún no preparados y `git diff --cached` los preparados para el commit. Conviene añadir rutas concretas para no incluir archivos de otra tarea.

Referencias: [git status](https://git-scm.com/docs/git-status) y [git diff](https://git-scm.com/docs/git-diff).

## 5. Conservación de las evidencias

Las capturas de esta actualización se extraen del Word sin modificar sus bytes. Cuando una imagen ya existe en el repositorio, se enlaza esa copia para evitar duplicarla. Los archivos incompletos se identifican como fragmentos; las reconstrucciones o propuestas se distinguen de la configuración observada.

El historial que antes ocupaba `docs/Base de datos.md` se conserva íntegro en [config/bbdd/evidencias/historial-original.txt](../config/bbdd/evidencias/historial-original.txt), mientras la memoria explica qué permite acreditar.

No se han aplicado configuraciones a las VMs ni se ha publicado un commit desde esta preparación. La integración de estos archivos en GitHub es un paso posterior.
