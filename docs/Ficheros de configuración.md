# Ficheros de configuración

[Volver al índice del proyecto](../README.md)

## Organización

Los archivos se guardan en [config/](../config/README.md), mientras que `docs/` explica el procedimiento, el motivo de los ajustes y las pruebas realizadas. Las capturas complementan los archivos de texto para mostrar la evidencia de la práctica.

## DNS y DHCP

| Configuración | Archivo | Ubicación en la máquina |
| --- | --- | --- |
| Servicio DNS y DHCP de DNS-N02 | [dnsmasq.conf](../config/dns-dhcp/dnsmasq.conf) | `/etc/dnsmasq.conf` |
| DHCP del cliente Linux | [netplan-cliente-linux.yaml](../config/dns-dhcp/netplan-cliente-linux.yaml) | `/etc/netplan/01-network-manager-all.yaml` |
| Red del servidor, versión completa propuesta | [netplan-servidor.yaml](../config/dns-dhcp/netplan-servidor.yaml) | `/etc/netplan/00-installer-config.yaml` |

El [inventario de DNS y DHCP](../config/dns-dhcp/README.md) diferencia las transcripciones de las capturas de las correcciones propuestas. Conserva también la evidencia de Netplan con la ruta incompleta y una alternativa de dnsmasq que añade `R-N02`.

La memoria del procedimiento, los fundamentos y las capturas están en [DNS y DHCP](<DNS y DHCP.md>). Las configuraciones del resto de servicios quedan fuera de esta actualización.
