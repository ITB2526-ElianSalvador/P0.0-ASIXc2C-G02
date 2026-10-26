# Configuración del router R-N02

[Procedimiento y pruebas](../../docs/router.md) · [Inventario general](../README.md)

Esta carpeta separa los ajustes técnicos de la memoria explicativa. Los archivos no se instalan automáticamente y no equivalen a una exportación completa de la configuración actual del router.

## Archivos y procedencia

| Archivo | Ubicación de referencia en R-N02 | Procedencia y alcance |
| --- | --- | --- |
| [netplan-router.yaml](netplan-router.yaml) | `/etc/netplan/50-cloud-init.yaml`, según la memoria anterior. | Contenido trasladado de la versión previa de `docs/router.md`. No hay captura del Netplan del router en el Word; pendiente de contraste con la VM. |
| [sysctl-router.conf](sysctl-router.conf) | Directiva de `/etc/sysctl.conf`. | Fragmento transcrito de la captura del reenvío IPv4. No sustituye al archivo completo. |
| [iptables-nat.txt](iptables-nat.txt) | Regla cargada con `iptables`; guardado IPv4 en `/etc/iptables/rules.v4`. | Comando exacto visible en la captura. Es texto documental, no un fichero completo para `iptables-restore`. |

## Revisión antes de utilizar los ajustes

Comprobar primero las interfaces y el Netplan efectivo de la VM. El YAML contiene la asociación descrita en el repositorio: `enp1s0` para Default, `enp2s0` para DMZ y `enp3s0` para Intranet. Los nombres no deben trasladarse a otra VM sin comprobarlos.

El fragmento de sysctl muestra la directiva que se editó en `/etc/sysctl.conf`. No debe copiarse sobre ese archivo completo, ya que se perderían sus demás ajustes.

La regla NAT debe revisarse antes de volver a añadirla para no crear duplicados. Una comprobación posible es:

```bash
sudo iptables -t nat -C POSTROUTING -o enp1s0 -j MASQUERADE
```

Un código de salida `0` indica que la regla coincide. Cualquier error debe revisarse antes de modificar las reglas.

## Configuración efectiva pendiente de incorporar

Faltan el Netplan obtenido directamente de R-N02 y una exportación de sus reglas completas. Para completar esta carpeta se pueden obtener con:

```bash
sudo cat /etc/netplan/50-cloud-init.yaml
sudo iptables-save
sudo cat /etc/iptables/rules.v4
```

La primera ruta debe adaptarse si la configuración efectiva usa otro nombre de archivo. Las salidas permitirán contrastar la configuración declarada, la cargada y la guardada. Estas órdenes se proponen para la revisión y no se han ejecutado en el router durante esta actualización.
