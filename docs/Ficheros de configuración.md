# Ficheros de configuración

[Volver al índice del proyecto](../README.md)

## Organización

Los archivos se conservan en [config/](../config/README.md). Los documentos de `docs/` explican el procedimiento, las decisiones y las pruebas. Las capturas están en la raíz, dentro de `imagenes/`, organizadas por equipo o componente: R-N02, W-N02, F-N02, PCL-N02, PCW-N02, B-N02, DNS y DHCP y ProofHub.

Los inventarios indican si cada archivo es una transcripción, un fragmento, una configuración procedente de una memoria anterior o una propuesta. Un historial de terminal y una captura parcial no equivalen a una configuración completa para instalar.

## Router R-N02

| Configuración | Archivo | Ubicación en la máquina |
| --- | --- | --- |
| Direccionamiento de las tres interfaces | [netplan-router.yaml](../config/router/netplan-router.yaml) | `/etc/netplan/50-cloud-init.yaml`, según la memoria anterior. |
| Reenvío IPv4 | [sysctl-router.conf](../config/router/sysctl-router.conf) | Fragmento de `/etc/sysctl.conf`. |
| Regla de enmascaramiento NAT | [iptables-nat.txt](../config/router/iptables-nat.txt) | Comando ejecutado con `iptables`; guardado IPv4 de referencia en `/etc/iptables/rules.v4`. |

El [inventario del router](../config/router/README.md) distingue la configuración trasladada de la memoria de las transcripciones de capturas. No se dispone de una exportación completa de `rules.v4`. El procedimiento y las pruebas están en [Router R-N02](router.md).

## DNS y DHCP

| Configuración | Archivo | Ubicación en la máquina |
| --- | --- | --- |
| Servicio DNS y DHCP de DNS-N02 | [dnsmasq.conf](../config/dns-dhcp/dnsmasq.conf) | `/etc/dnsmasq.conf` |
| DHCP del cliente Linux | [netplan-cliente-linux.yaml](../config/dns-dhcp/netplan-cliente-linux.yaml) | `/etc/netplan/01-network-manager-all.yaml` |
| Red del servidor, versión completa propuesta | [netplan-servidor.yaml](../config/dns-dhcp/netplan-servidor.yaml) | `/etc/netplan/00-installer-config.yaml` |

El [inventario de DNS y DHCP](../config/dns-dhcp/README.md) conserva la transcripción incompleta de Netplan y diferencia las propuestas. El archivo actual de dnsmasq documenta `R-NCC` y `R`; la consulta del cliente a `R-N02` requiere contrastar los nombres con el servicio efectivo antes de modificar el archivo. La memoria está en [DNS y DHCP](<DNS y DHCP.md>).

## Clientes

Se reutiliza el [Netplan Linux ya conservado](../config/dns-dhcp/netplan-cliente-linux.yaml). La nueva captura muestra las mismas directivas activas. El [inventario de clientes](../config/clientes/README.md) explica la configuración observada en Windows y distingue las concesiones DHCP de direcciones estáticas. Las pruebas están en [Clientes](Clientes.md).

## FTP F-N02

| Configuración | Archivo | Ubicación en la máquina |
| --- | --- | --- |
| Red estática del FTP | [netplan-ftp.yaml](../config/ftp/netplan-ftp.yaml) | `/etc/netplan/00-installer-config.yaml` |
| Opciones visibles de vsftpd | [vsftpd-fragmento.conf](../config/ftp/vsftpd-fragmento.conf) | Fragmento de `/etc/vsftpd.conf`. |

El [inventario de FTP](../config/ftp/README.md) detalla la procedencia. El fragmento no sustituye al archivo completo del servicio. Las pruebas están en [Servidor FTP](<Servidor FTP.md>).

## Web W-N02

| Configuración o evidencia | Archivo | Ubicación en la máquina |
| --- | --- | --- |
| Red estática del servidor web | [netplan-web.yaml](../config/web/netplan-web.yaml) | `/etc/netplan/00-installer-config.yaml` |
| Inicio visible del código PHP | [index.php.fragmento.txt](../config/web/evidencias/index.php.fragmento.txt) | Fragmento de `/var/www/html/index.php`. |

El [inventario web](../config/web/README.md) identifica el código que falta y los ajustes aún no exportados. El fragmento PHP no se puede desplegar como aplicación completa. La memoria está en [Servidor web y aplicación](<Servidor web, SSH y aplicación de prueba.md>).

## Base de datos

Se conserva el [historial original](../config/bbdd/evidencias/historial-original.txt) que figuraba en la memoria anterior. El [inventario de base de datos](../config/bbdd/README.md) indica los archivos SQL y de sistema pendientes de incorporar. La explicación y las evidencias disponibles están en [Base de datos](<Base de datos.md>).
