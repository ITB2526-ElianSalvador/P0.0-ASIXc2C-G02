# Configuración de los clientes

[Memoria y pruebas](../../docs/Clientes.md) · [Inventario general](../README.md)

## Cliente Linux

El archivo [netplan-cliente-linux.yaml](../dns-dhcp/netplan-cliente-linux.yaml) ya estaba incorporado en DNS y DHCP y coincide, en sus directivas activas, con la captura del Word actualizado. Se reutiliza esa copia como referencia de `/etc/netplan/01-network-manager-all.yaml` de PCL-N02, evitando crear otra versión.

La interfaz visible es `enp1s0` con `dhcp4: true`. La dirección `192.168.20.112/24` y la ruta por `192.168.20.1` son resultados observados, no valores que deban fijarse en ese YAML. El nombre que figura en `/etc/hostname` es `PCL-N02`.

El archivo conserva un comentario sobre su procedencia anterior. La nueva captura confirma las mismas directivas; no acredita toda la configuración de todos los archivos Netplan de la VM.

## Cliente Windows

Las capturas muestran la creación de `bchecker`, el cambio a PCW-N02 y los parámetros de `ipconfig /all`. No se ha aportado una exportación de configuración de Windows para instalar.

La dirección `192.168.20.148` procede de DHCP. El gateway observado es `192.168.20.1` y el servidor DHCP y DNS es `192.168.20.10`. Estos resultados no se convierten en un script de IP estática.

## Evidencias pendientes

Quedan por obtener la configuración efectiva del resolvedor Linux, la comprobación de su cuenta `bchecker` y las pruebas DNS y web identificadas en ambos clientes. Las capturas originales y los resultados disponibles están enlazados en la memoria.
