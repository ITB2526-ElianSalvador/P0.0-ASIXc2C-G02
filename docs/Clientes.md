# Clientes (PCL-N02 y PCW-N02)

## 1. Objetivo

Se configuran los dos PCs cliente de la Intranet (ITB 1, red `192.168.20.0/24`): uno con Linux (**PCL-N02**) y otro con Windows (**PCW-N02**). Ambos obtienen su IP por DHCP desde **DNS-N02** (`192.168.20.10`) y resuelven nombres con el DNS de esa misma máquina.

| Equipo | Sistema | Red | IP | Responsable |
|---|---|---|---|---|
| PCL-N02 | Ubuntu Desktop | Intranet (ITB 1) | `192.168.20.112` (DHCP) | Faiver |
| PCW-N02 | Windows (plantilla Isard) | Intranet (ITB 1) | `192.168.20.148` (DHCP) | Faiver |

Los dos equipos tienen una sola tarjeta de red conectada a ITB 1, de modo que todo su tráfico pasa por el router R-N02 (`192.168.20.1`).

---
## Cliente Linux:

1. Primero le cambiamos el nombre al cliente Linux por **PCL-N02**. El nombre se guarda en el fichero `/etc/hostname`, así que lo editamos con nano y dejamos una sola línea con el nuevo nombre. Hace falta reiniciar la sesión para que el prompt muestre `isard@PCL-N02`.

```bash
   sudo nano /etc/hostname
```

   ![Cambio de hostname](<img width="563" height="183" alt="image" src="https://github.com/user-attachments/assets/dd40d5e8-9cfe-43a2-a8b8-1a101e96ca94" />
)

2. Después configuramos el netplan del cliente y le ponemos **dhcp4: true** para que el DHCP me dé una dirección IP automáticamente. Netplan es la herramienta de Ubuntu que lee la configuración de red de los ficheros YAML de `/etc/netplan/`. Con `dhcp4: true` el equipo no lleva una IP fija: la pide al servidor DHCP al arrancar. Por eso en el cliente no ponemos ni IP, ni gateway, ni DNS, porque el DHCP los entrega.

```bash
   sudo nano /etc/netplan/01-network-manager-all.yaml
![Cambio del Netplan] (<img width="590" height="164" alt="image" src="https://github.com/user-attachments/assets/5d40e3e8-036e-4d72-b3b3-5c0cf6829744" />
)

3. Ahora comprobamos con **ip a** que mi IP esté en el rango que asignó el DHCP (100-150). `ip a` muestra todas las tarjetas de red del equipo y sus direcciones. La IP que aparece en `enp1s0` es **192.168.20.112**. Entonces sí funcionó, porque está en el rango asignado en el `dhcp-range` de dnsmasq (`192.168.20.100` a `192.168.20.150`).

```bash
   ip a
```
 ![ip a](<img width="893" height="322" alt="image" src="https://github.com/user-attachments/assets/293c49c3-34ba-41bb-9c69-86e91a717255" />
)

