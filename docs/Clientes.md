# Clientes PCL-N02 y PCW-N02

[Volver al proyecto](../README.md) · [Arquitectura](arquitectura.md) · [Configuración de clientes](../config/clientes/README.md)

## 1. Objetivo

Los dos clientes sirven para probar los servicios de la infraestructura, uno desde Linux y otro desde Windows. Los dos están en la Intranet `192.168.20.0/24` (ITB 1 en Isard) y usan R-N02 como puerta de enlace y DNS-N02 como servidor DNS y DHCP.

| Equipo | Sistema | IPv4 | Asignación | Puerta de enlace |
| --- | --- | --- | --- | --- |
| PCL-N02 | Ubuntu con entorno gráfico (versión sin confirmar) | `192.168.20.112/24` | DHCP | `192.168.20.1` |
| PCW-N02 | Windows (versión sin confirmar) | `192.168.20.148/24` | DHCP | `192.168.20.1` |

Las IP son las que salen en las capturas y pueden cambiar, porque las reparte el DHCP. El rango configurado en DNS-N02 va de `192.168.20.100` a `192.168.20.150`, con concesiones de 12 horas. Falta una captura de las tarjetas de red de Isard para confirmar que los clientes están en ITB 1.

## 2. Cliente Linux (PCL-N02)

### 2.1. Nombre del equipo

Para poner el nombre se editó el archivo `/etc/hostname`:

```bash
sudo nano /etc/hostname
```

Contenido del archivo:

```text
PCL-N02
```

![Nombre PCL-N02 en el archivo hostname](../imagenes/PCL-N02/01-hostname-linux.png)

*Figura 1. Edición del hostname. En las capturas siguientes el prompt ya muestra PCL-N02.*

Editar el archivo deja el nombre guardado de forma permanente, pero para que se aplique en el sistema que ya está arrancado hay que reiniciar o usar las herramientas del sistema. Abrir otra terminal no basta.

### 2.2. Red por DHCP

El archivo de red es `/etc/netplan/01-network-manager-all.yaml`:

```yaml
network:
  version: 2
  ethernets:
    enp1s0:
      dhcp4: true
```

Con `dhcp4: true` el cliente pide la configuración al servidor DHCP. La IP `.112` no se escribe a mano, la recibe en la concesión. Tampoco se pusieron a mano la ruta por defecto ni los DNS.

![Netplan del cliente Linux con DHCP](../imagenes/PCL-N02/02-netplan-dhcp.png)

*Figura 2. Configuración de `enp1s0` con DHCP activado. El comentario del archivo menciona NetworkManager.*

La copia del YAML está en [config/dns-dhcp/netplan-cliente-linux.yaml](../config/dns-dhcp/netplan-cliente-linux.yaml), así que se usa ese mismo archivo y no se mantienen dos versiones. Para probar un cambio antes de aplicarlo se puede usar `sudo netplan generate` y `sudo netplan try`.

Referencia: [configuración YAML de Netplan](https://netplan.readthedocs.io/en/stable/netplan-yaml/).

### 2.3. Dirección recibida

Se ejecutó:

```bash
ip a
```

![Dirección dinámica del cliente Linux](../imagenes/PCL-N02/03-direccion-ip-linux.png)

*Figura 3. `enp1s0` está activa y tiene `192.168.20.112/24`, marcada como `dynamic`.*

La IP está dentro del rango del proyecto. Que venga por DHCP se ve por el YAML, por la marca `dynamic` y por la ruta con `proto dhcp`. Estar dentro del rango no demuestra nada por sí solo.

### 2.4. Tabla de rutas

```bash
ip route
```

La línea principal es:

```text
default via 192.168.20.1 dev enp1s0 proto dhcp src 192.168.20.112 metric 100
```

![Tabla de rutas del cliente Linux](../imagenes/PCL-N02/04-rutas-linux.png)

*Figura 4. La puerta de enlace por defecto es R-N02 y la ruta llegó por DHCP.*

La red `192.168.20.0/24` se alcanza directamente por la interfaz. Para llegar a la DMZ o a otras redes el cliente pasa por `192.168.20.1`.

### 2.5. Resolución de nombres

```bash
nslookup R-N02 192.168.20.10
```

La respuesta es `R-N02` con la dirección `192.168.20.1`:

![Consulta DNS de R-N02 desde Linux](../imagenes/PCL-N02/05-resolucion-dns.png)

*Figura 5. DNS-N02 responde bien a la consulta sobre el nombre del router.*

En esta prueba se indicó el servidor `192.168.20.10` a mano. Para demostrar que el cliente lo usa por defecto habría que sacar su configuración efectiva (por ejemplo con `resolvectl status`) y repetir la consulta sin indicar servidor. Además, el archivo de dnsmasq del repositorio todavía tiene `R-NCC` y `R`, mientras que esta prueba responde a `R-N02`. Hay que revisar la configuración actual del servicio antes de actualizar ese archivo.

### 2.6. Acceso al FTP de la DMZ

En la parte de FTP del Word hay una terminal `isard@PCL-N02` ejecutando:

```bash
ftp 192.168.10.11
```

![Inicio de sesión FTP desde PCL-N02](../imagenes/F-N02/08-login-ftp-desde-linux.png)

*Figura 6. Inicio de sesión con `bchecker` y respuesta `230 Login successful`.*

La captura demuestra que se pudo iniciar sesión en F-N02 desde la Intranet, pasando por R-N02 hacia la DMZ. Todavía no hay un listado, una subida ni una descarga. El detalle está en [Servidor FTP](<Servidor FTP.md>).

## 3. Cliente Windows (PCW-N02)

### 3.1. Usuario de revisión

Desde una consola con permisos de administrador se creó el usuario local `bchecker`, con la contraseña de laboratorio indicada en el [README](../README.md). Windows confirmó que el comando se completó correctamente.

![Creación del usuario local bchecker en Windows](../imagenes/PCW-N02/01-usuario-windows.png)

*Figura 7. Ejecución de `net user ... /add` y confirmación de que se creó la cuenta.*

Con esto solo se sabe que la cuenta existe. No se ha comprobado que se inicie sesión con ella ni que esté en el grupo Administradores. En el cliente Linux tampoco hay capturas de la creación de `bchecker`, así que queda pendiente comprobarlo con `getent passwd bchecker`.

### 3.2. Nombre del equipo

Se ejecutó:

```powershell
powershell -Command "Rename-Computer -NewName PCW-N02"
```

![Cambio del nombre del equipo Windows](../imagenes/PCW-N02/02-cambio-hostname.png)

*Figura 8. Windows avisa de que el cambio se aplicará al reiniciar.*

No hay captura del reinicio, pero la captura posterior de `ipconfig /all` ya muestra **PCW-N02** como nombre del host, así que el cambio se aplicó.

Referencia: [Rename-Computer de Microsoft](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.management/rename-computer).

### 3.3. Parámetros recibidos por DHCP

```cmd
ipconfig /all
```

![Configuración IP completa del cliente Windows](../imagenes/PCW-N02/03-ipconfig-windows.png)

*Figura 9. Configuración del adaptador Ethernet 2 de PCW-N02.*

| Parámetro | Valor |
| --- | --- |
| DHCP habilitado | Sí |
| Dirección IPv4 | `192.168.20.148` |
| Máscara | `255.255.255.0` |
| Puerta de enlace | `192.168.20.1` |
| Servidor DHCP | `192.168.20.10` |
| Servidor DNS | `192.168.20.10` |

La captura muestra que DNS-N02 hace de servidor DHCP y de DNS, y que la IP está dentro del rango configurado. No hay ninguna consulta DNS hecha desde Windows.

Referencia: [ipconfig de Microsoft](https://learn.microsoft.com/en-us/windows-server/administration/windows-commands/ipconfig).

## 4. Acceso a la aplicación web

El Word actualizado incluye el formulario y la tabla de resultados que se ven en `http://192.168.10.10/index.php` desde el navegador de un cliente. Las capturas están en la [documentación web](<Servidor web, SSH y aplicación de prueba.md>).

En esas dos capturas no se ve el nombre del equipo, así que no se puede decir si se hicieron desde PCL-N02 o desde PCW-N02, ni que se hayan repetido en los dos.

## 5. Pendiente de comprobar

| Revisión | Linux | Windows |
| --- | --- | --- |
| Nombre del equipo | Se ve en el prompt | Se ve en `ipconfig /all` |
| IP por DHCP | Comprobado (dirección dinámica y rutas) | Comprobado (`ipconfig /all`) |
| Consulta a DNS-N02 | Responde a `R-N02` | Falta prueba |
| DNS por defecto | Falta comprobar el resolvedor | El DNS está configurado, falta la consulta |
| Sesión FTP | Comprobado con `bchecker` | Falta prueba |
| Aplicación web | Captura sin identificar el cliente | Captura sin identificar el cliente |
| Usuario local `bchecker` | Falta comprobarlo | Creación comprobada |

Para cerrar la validación falta identificar cada equipo antes de hacer las capturas, comprobar que llegan al gateway y repetir las pruebas de DNS y de acceso web en los dos. Con la revisión de las tarjetas de Isard se confirmará que solo tienen la conexión interna prevista.
