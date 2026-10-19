# P0.0-ASIXc2gC-G02 · Despliegue de infraestructura

Módulo 0379 · Institut Tecnològic Barcelona · ASIX · Equipo N02

## Introducción

En esta práctica montamos desde cero, en Isard, la infraestructura de una aplicación en capas. Hay un router que une tres redes (NAT, DMZ e Intranet) y varios servidores: web con SSH, base de datos MySQL, DNS con DHCP y FTP. Para comprobar que todo funciona, hay dos PCs cliente (uno Linux y otro Windows) desde los que se abre una aplicación web con login. La aplicación muestra el listado de equipamientos de educación de Barcelona, cargado desde un CSV en la base de datos.

Toda la configuración y la documentación está en este repositorio.

## Equipo

| Persona | Parte de la práctica |
| --- | --- |
| Elian | Router, ProofHub y diagrama |
| Daniel | Repositorio, DNS y DHCP |
| Faiver | Servidor web, SSH, PCs cliente y aplicación |
| Elias | Base de datos y FTP |

## Redes

| Red | Red de Isard | Rango |
| --- | --- | --- |
| NAT (internet) | Default | por DHCP |
| DMZ | ITB 2 | 192.168.10.0/24 |
| Intranet | ITB 1 | 192.168.20.0/24 |

## Equipos e IPs

| Equipo | Hostname | Red | IP |
| --- | --- | --- | --- |
| Router | R-N02 | NAT / DMZ / Intranet | DHCP / 192.168.10.1 / 192.168.20.1 |
| Servidor web y SSH | W-N02 | DMZ | 192.168.10.10 |
| Servidor FTP | F-N02 | DMZ | 192.168.10.11 |
| DNS y DHCP | DNS-N02 | Intranet | 192.168.20.10 |
| Base de datos | B-N02 | Intranet | 192.168.20.20 |
| PC Linux | PCL-N02 | Intranet | DHCP (192.168.20.100 a .150) |
| PC Windows | PCW-N02 | Intranet | DHCP (192.168.20.100 a .150) |

Usuario de acceso en todos los sistemas: `bchecker` / `bchecker121`.

## Índice de documentación

- [Arquitectura](<docs/Arquitectura.md>)
- [Router](<docs/Router.md>)
- [Servidor web, SSH y aplicación de prueba](<docs/Servidor web, SSH y aplicación de prueba.md>)
- [Base de datos](<docs/Base de datos.md>)
- [DNS y DHCP](<docs/DNS y DHCP.md>)
- [Servidor FTP](<docs/Servidor FTP.md>)
- [Clientes](<docs/Clientes.md>)
- [Control de versiones con Git](<docs/Control de versiones con Git.md>)
- [Ficheros de configuración](<config/>)
