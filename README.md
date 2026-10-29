# P0.0-ASIXc2C-G02 · Despliegue de infraestructura

Módulo 0379 · Institut Tecnològic Barcelona · ASIX · Equipo N02

## Introducción

El proyecto despliega en Isard una infraestructura para una aplicación web en capas. Un router conecta la red exterior, la DMZ y la Intranet. Los servicios se distribuyen entre un servidor web con PHP, un servidor de base de datos, DNS y DHCP y un servidor FTP. Dos clientes, Linux y Windows, permiten comprobar el acceso a los servicios.

La aplicación muestra un formulario y un listado de equipamientos de educación después del acceso. La memoria distingue los resultados capturados de las configuraciones descritas y de las pruebas pendientes. El repositorio conserva la documentación en Markdown, los archivos de configuración disponibles y sus evidencias.

![Plantilla Ubuntu Server seleccionada](../imagenes/Arquitectura (1).png)

## Equipo

| Persona | Parte asignada en el reparto del proyecto |
| --- | --- |
| Elian | Router, ProofHub y diagrama. |
| Daniel | Repositorio, DNS y DHCP. |
| Faiver | Servidor web, SSH, PCs cliente y aplicación. |
| Elias | Base de datos y FTP. |

## Redes

| Red | Red de Isard | Rango |
| --- | --- | --- |
| Exterior con salida a internet | Default | Asignado por DHCP. |
| DMZ | ITB 2 | `192.168.10.0/24` |
| Intranet | ITB 1 | `192.168.20.0/24` |

## Equipos e IPs

| Equipo | Hostname previsto | Red | IPv4 |
| --- | --- | --- | --- |
| Router | R-N02 | Exterior, DMZ e Intranet | DHCP; `192.168.10.1`; `192.168.20.1`. |
| Servidor web y SSH previsto | W-N02 | DMZ | `192.168.10.10` |
| Servidor FTP | F-N02 | DMZ | `192.168.10.11` |
| DNS y DHCP | DNS-N02 | Intranet | `192.168.20.10` |
| Base de datos | B-N02 | Intranet | `192.168.20.20`, según el diseño y el destino de PHP. |
| PC Linux | PCL-N02 | Intranet | DHCP; observada `192.168.20.112`. |
| PC Windows | PCW-N02 | Intranet | DHCP; observada `192.168.20.148`. |

Los clientes utilizan el rango DHCP `192.168.20.100` a `192.168.20.150`. Las direcciones observadas no son direcciones fijas. Las capturas de preparación de la base de datos muestran el nombre provisional **B-NCC**, pendiente de contrastar con B-N02.

Usuario de revisión requerido en los sistemas: `bchecker` / `bchecker121`. Las comprobaciones de cada cuenta se indican en su documento; una cuenta del sistema operativo y una cuenta MySQL se verifican por separado.

## Índice de documentación

- [Arquitectura](docs/arquitectura.md)
- [Router](docs/router.md)
- [Servidor web, SSH y aplicación de prueba](<docs/Servidor web, SSH y aplicación de prueba.md>)
- [Base de datos](<docs/Base de datos.md>)
- [DNS y DHCP](<docs/DNS y DHCP.md>)
- [Servidor FTP](<docs/Servidor FTP.md>)
- [Clientes](docs/Clientes.md)
- [Control de versiones con Git](<docs/Control de versiones con Git.md>)
- [Planificación con ProofHub](<docs/Planificacion con ProofHub.md>)
- [Inventario de configuración](<docs/Ficheros de configuración.md>)
- [Carpeta de configuración](config/README.md)

## Estado documentado

| Parte | Resultado acreditado | Validación que falta |
| --- | --- | --- |
| Router | Reenvío IPv4, regla NAT y guardado de reglas. | Configuración efectiva completa y prueba tras reinicio. |
| Clientes | Parámetros DHCP de Linux y Windows; consulta DNS desde Linux. | Repetir DNS y web identificando ambos equipos; comprobar la cuenta Linux. |
| FTP | Servicio activo y login correcto desde PCL-N02. | Listado, subida y descarga; prueba desde Windows. |
| Web y aplicación | Apache activo, formulario y tabla tras login. | Código completo, identificación del cliente y pruebas de funciones restantes. |
| Base de datos | Preparación registrada, destino visible en PHP y datos presentados por la aplicación. | Configuración efectiva, esquema, permisos y carga reproducible. |
| SSH en W-N02 | Función prevista. | Servicio y sesión remota identificados; conexión con GitHub si corresponde. |
| Arquitectura | Diseño y relaciones entre servicios reconstruidos. | Interfaces finales y política de filtrado contrastadas. |

La documentación se basa en el Word actualizado y en la revisión del repositorio del 26 de octubre de 2026. Los estados de ProofHub son vistas conservadas en capturas, no una consulta al tablero en tiempo real.
