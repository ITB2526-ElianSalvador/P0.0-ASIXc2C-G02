# Configuración de DNS y DHCP

[Explicación, decisiones y pruebas](<../../docs/DNS y DHCP.md>) · [Inventario general](<../../docs/Ficheros de configuración.md>)

## Archivos y procedencia

| Archivo del repositorio | Equipo y ubicación de referencia | Estado |
| --- | --- | --- |
| [dnsmasq.conf](dnsmasq.conf) | DNS-N02: `/etc/dnsmasq.conf` | Directivas transcritas de la captura de la página 5. Conserva `R-NCC` y `R`. |
| [netplan-cliente-linux.yaml](netplan-cliente-linux.yaml) | PCL-N02: `/etc/netplan/01-network-manager-all.yaml` | Configuración activa visible en la captura de la página 8. |
| [evidencias/netplan-servidor-captura.yaml.txt](evidencias/netplan-servidor-captura.yaml.txt) | DNS-N02: `/etc/netplan/00-installer-config.yaml` | Transcripción de la página 4; falta `via` en la ruta predeterminada. Se guarda como texto de evidencia. |
| [netplan-servidor.yaml](netplan-servidor.yaml) | DNS-N02: `/etc/netplan/00-installer-config.yaml` | Reconstrucción propuesta que añade `via: 192.168.20.1`. Pendiente de contraste con la VM. |
| [propuestas/dnsmasq.conf](propuestas/dnsmasq.conf) | DNS-N02: `/etc/dnsmasq.conf` | Variante propuesta que añade el nombre `R-N02`. Pendiente de contraste y aplicación. |

Los archivos contienen los ajustes activos visibles y comentarios de procedencia. No reproducen todos los comentarios o posibles ajustes no visibles del archivo de sistema original.

## Antes de utilizarlos

La captura original y la versión propuesta se conservan por separado para poder revisar las diferencias. **No deben cargarse las dos versiones de dnsmasq a la vez**: representan alternativas para un mismo archivo de destino. Los archivos de `evidencias/` se conservan como prueba documental, no para instalarlos.

En DNS-N02 debe revisarse la configuración actual antes de sustituirla. Para comparar las directivas activas y la red:

```bash
sudo grep -Ev '^[[:space:]]*(#|$)' /etc/dnsmasq.conf
sudo cat /etc/netplan/00-installer-config.yaml
ip route
```

Si la configuración efectiva ya contiene `R-N02` o una ruta completa, debe incorporarse esa versión y una nueva captura al repositorio. Los archivos de este directorio no se han aplicado desde GitHub a ninguna VM.

## Validación en la máquina

Después de guardar la configuración elegida de dnsmasq en `/etc/dnsmasq.conf`, comprobar la sintaxis y, solo si no hay errores, reiniciar:

```bash
sudo dnsmasq --test
sudo systemctl restart dnsmasq
sudo systemctl status dnsmasq
```

Para Netplan, el archivo debe integrarse en la configuración existente de `/etc/netplan/`, respetando el nombre real de la interfaz y evitando definiciones contradictorias en varios archivos. Validar antes de aplicar:

```bash
sudo netplan generate
sudo netplan try
```

Estas órdenes son instrucciones de reproducción y revisión. Su ejecución adicional no está acreditada por las capturas originales.
