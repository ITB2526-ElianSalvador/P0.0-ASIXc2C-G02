# Ficheros de configuración

[Volver al índice del proyecto](../README.md)

## Organización

Los archivos se guardan en [config/](../config/README.md), mientras que `docs/` explica el procedimiento, el motivo de los ajustes y las pruebas realizadas. Las capturas se guardan en la raíz, dentro de `imagenes/`: [router R-N02](../imagenes/R-N02/) y [DNS y DHCP](<../imagenes/DNS i DHCP/>). Complementan los archivos de texto para mostrar la evidencia de la práctica.

## Router R-N02

| Configuración | Archivo | Ubicación en la máquina |
| --- | --- | --- |
| Direccionamiento de las tres interfaces | [netplan-router.yaml](../config/router/netplan-router.yaml) | `/etc/netplan/50-cloud-init.yaml`, según la memoria anterior. |
| Reenvío IPv4 | [sysctl-router.conf](../config/router/sysctl-router.conf) | Fragmento de `/etc/sysctl.conf`. |
| Regla de enmascaramiento NAT | [iptables-nat.txt](../config/router/iptables-nat.txt) | Comando ejecutado con `iptables`; el guardado IPv4 se referencia en `/etc/iptables/rules.v4`. |

El [inventario del router](../config/router/README.md) distingue la configuración trasladada de la memoria anterior de las transcripciones de capturas. No se dispone de una exportación completa de `rules.v4`. El procedimiento, la explicación y las pruebas están en [Router R-N02](router.md).

## DNS y DHCP

| Configuración | Archivo | Ubicación en la máquina |
| --- | --- | --- |
| Servicio DNS y DHCP de DNS-N02 | [dnsmasq.conf](../config/dns-dhcp/dnsmasq.conf) | `/etc/dnsmasq.conf` |
| DHCP del cliente Linux | [netplan-cliente-linux.yaml](../config/dns-dhcp/netplan-cliente-linux.yaml) | `/etc/netplan/01-network-manager-all.yaml` |
| Red del servidor, versión completa propuesta | [netplan-servidor.yaml](../config/dns-dhcp/netplan-servidor.yaml) | `/etc/netplan/00-installer-config.yaml` |

El [inventario de DNS y DHCP](../config/dns-dhcp/README.md) diferencia las transcripciones de las capturas de las correcciones propuestas. Conserva también la evidencia de Netplan con la ruta incompleta y una alternativa de dnsmasq que añade `R-N02`.

La memoria del procedimiento, los fundamentos y las capturas están en [DNS y DHCP](<DNS y DHCP.md>). Las configuraciones de los servicios que no figuran en este inventario quedan pendientes de incorporar.

