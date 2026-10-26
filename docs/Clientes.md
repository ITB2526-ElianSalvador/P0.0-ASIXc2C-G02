# Clientes PCL-N02 y PCW-N02

[Volver al proyecto](../README.md) · [Arquitectura](arquitectura.md) · [Configuración de clientes](../config/clientes/README.md)

## 1. Objetivo y situación documentada

Los clientes permiten comprobar los servicios de la infraestructura desde un puesto Linux y otro Windows. Ambos pertenecen a la Intranet `192.168.20.0/24`, asociada a ITB 1 en Isard, y deben utilizar R-N02 como puerta de enlace y DNS-N02 como servidor DNS y DHCP. El responsable asignado en el repositorio es Faiver.

| Equipo | Sistema documentado | IPv4 observada | Asignación | Puerta de enlace |
| --- | --- | --- | --- | --- |
| PCL-N02 | Ubuntu con entorno gráfico; versión exacta no acreditada. | `192.168.20.112/24` | DHCP | `192.168.20.1` |
| PCW-N02 | Windows; edición y versión exactas no acreditadas. | `192.168.20.148/24` | DHCP | `192.168.20.1` |

Estas direcciones son concesiones observadas en capturas y pueden cambiar. El rango configurado en DNS-N02 es `192.168.20.100` a `192.168.20.150`, con duración de 12 horas. La asociación ITB 1 procede del esquema del proyecto; la comprobación final de las tarjetas de Isard queda pendiente de una captura específica.

## 2. Cliente Linux

### 2.1. Nombre del equipo

Se editó `/etc/hostname` para guardar el nombre del cliente:

```bash
sudo nano /etc/hostname
```

Contenido mostrado:

```text
PCL-N02
```

![Nombre PCL-N02 en el archivo hostname](../imagenes/PCL-N02/01-hostname-linux.png)

*Figura 1. Edición del nombre del cliente. En las capturas posteriores el prompt ya muestra PCL-N02.*

La edición del archivo configura el nombre persistente; el cambio del nombre en ejecución puede requerir aplicarlo con las herramientas del sistema o reiniciar. No se afirma que baste con abrir otra terminal para aplicar el nombre del sistema.

### 2.2. Configuración de red por DHCP

El archivo mostrado es `/etc/netplan/01-network-manager-all.yaml`:

```yaml
network:
  version: 2
  ethernets:
    enp1s0:
      dhcp4: true
```

Con DHCP, el equipo solicita los parámetros de red al servidor. No se introduce la dirección `.112` como dirección estática: la recibe mediante una concesión. Tampoco se añaden manualmente una ruta predeterminada o servidores DNS en este fragmento.

![Netplan del cliente Linux con DHCP habilitado](../imagenes/PCL-N02/02-netplan-dhcp.png)

*Figura 2. Configuración activa visible de `enp1s0`. El comentario del archivo menciona NetworkManager, pero no aparece una directiva `renderer`; se conserva lo realmente mostrado.*

La copia del YAML ya está en [config/dns-dhcp/netplan-cliente-linux.yaml](../config/dns-dhcp/netplan-cliente-linux.yaml). Se reutiliza ese archivo para evitar mantener dos versiones de la misma configuración. Para revisar un cambio antes de aplicarlo se pueden utilizar `sudo netplan generate` y `sudo netplan try`; estas órdenes adicionales no aparecen ejecutadas en las capturas de clientes.

Referencia: [configuración YAML de Netplan](https://netplan.readthedocs.io/en/stable/netplan-yaml/).

### 2.3. Dirección recibida

Se ejecutó:

```bash
ip a
```

![Dirección dinámica del cliente Linux](../imagenes/PCL-N02/03-direccion-ip-linux.png)

*Figura 3. `enp1s0` está activa y tiene `192.168.20.112/24`, marcada como `dynamic`.*

La dirección está dentro del rango del proyecto. La combinación del YAML, el indicador `dynamic` y la ruta con `proto dhcp` aporta evidencia de asignación por DHCP; estar dentro del rango, por sí solo, no lo demostraría.

### 2.4. Tabla de rutas

```bash
ip route
```

La línea principal mostrada es:

```text
default via 192.168.20.1 dev enp1s0 proto dhcp src 192.168.20.112 metric 100
```

![Tabla de rutas del cliente Linux](../imagenes/PCL-N02/04-rutas-linux.png)

*Figura 4. La puerta de enlace predeterminada es R-N02 y su ruta se ha recibido por DHCP.*

La red `192.168.20.0/24` es directamente accesible por la interfaz local. Para llegar a la DMZ u otras redes, el cliente utiliza `192.168.20.1`.

### 2.5. Resolución de nombres

```bash
nslookup R-N02 192.168.20.10
```

La respuesta visible es `R-N02` con dirección `192.168.20.1`:

![Consulta DNS de R-N02 desde Linux](../imagenes/PCL-N02/05-resolucion-dns.png)

*Figura 5. DNS-N02 responde correctamente a una consulta explícita sobre el nombre del router.*

Esta prueba utiliza directamente el servidor `192.168.20.10`. Para acreditar además que el cliente usa ese DNS por defecto, falta registrar su configuración efectiva, por ejemplo mediante `resolvectl status`, y repetir la consulta sin indicar servidor. El archivo de dnsmasq del repositorio conserva `R-NCC` y `R`, mientras esta prueba responde a `R-N02`; debe contrastarse la configuración actual del servicio antes de actualizar ese archivo.

### 2.6. Acceso al FTP de la DMZ

La sección FTP del Word muestra una terminal identificada como `isard@PCL-N02` ejecutando:

```bash
ftp 192.168.10.11
```

![Inicio de sesión FTP desde PCL-N02](../imagenes/F-N02/08-login-ftp-desde-linux.png)

*Figura 6. Inicio de sesión con `bchecker` y respuesta `230 Login successful`.*

La captura acredita una sesión de control autenticada con F-N02. En la topología prevista, este acceso comunica la Intranet con la DMZ a través de R-N02. No se muestra todavía un listado, una subida o una descarga. El detalle está en [Servidor FTP](<Servidor FTP.md>).

## 3. Cliente Windows

### 3.1. Usuario de revisión

En una consola con permisos de administrador se creó el usuario local `bchecker` con la contraseña de laboratorio indicada en el [README](../README.md). Windows devolvió que el comando se había completado correctamente.

![Creación del usuario local bchecker en Windows](../imagenes/PCW-N02/01-usuario-windows.png)

*Figura 7. Ejecución de `net user ... /add` y confirmación de creación de la cuenta.*

Esta creación no demuestra que se haya iniciado sesión con esa cuenta ni que pertenezca al grupo Administradores. En el cliente Linux, las capturas específicas de este apartado tampoco muestran la creación de `bchecker`; queda pendiente su comprobación con `getent passwd bchecker`.

### 3.2. Nombre del equipo

Se ejecutó:

```powershell
powershell -Command "Rename-Computer -NewName PCW-N02"
```

![Cambio del nombre del equipo Windows](../imagenes/PCW-N02/02-cambio-hostname.png)

*Figura 8. Windows avisa de que el cambio será efectivo después de reiniciar.*

La captura posterior de `ipconfig /all` ya muestra **PCW-N02** como nombre del host. Acredita el nombre efectivo en ese momento, aunque no se adjunte una captura del reinicio.

Referencia: [Rename-Computer de Microsoft](https://learn.microsoft.com/en-us/powershell/module/microsoft.powershell.management/rename-computer).

### 3.3. Parámetros obtenidos por DHCP

```cmd
ipconfig /all
```

![Configuración IP completa del cliente Windows](../imagenes/PCW-N02/03-ipconfig-windows.png)

*Figura 9. Configuración efectiva del adaptador Ethernet 2 de PCW-N02.*

| Parámetro visible | Valor |
| --- | --- |
| DHCP habilitado | Sí |
| Dirección IPv4 | `192.168.20.148` |
| Máscara | `255.255.255.0` |
| Puerta de enlace | `192.168.20.1` |
| Servidor DHCP | `192.168.20.10` |
| Servidor DNS | `192.168.20.10` |

La captura identifica expresamente a DNS-N02 como servidor DHCP y DNS, además de mostrar una dirección dentro del rango configurado. No incluye una consulta DNS realizada desde Windows.

Referencia: [ipconfig de Microsoft](https://learn.microsoft.com/en-us/windows-server/administration/windows-commands/ipconfig).

## 4. Acceso a la aplicación web

El Word actualizado incorpora el formulario y la tabla obtenida en `http://192.168.10.10/index.php` desde un navegador de cliente. Estas evidencias se conservan en la [documentación web](<Servidor web, SSH y aplicación de prueba.md>).

No se ve el hostname del cliente en esas dos capturas. Se puede documentar la prueba de navegador, pero no atribuirla de forma concluyente a PCL-N02 o a PCW-N02, ni afirmar que se ha repetido en ambos.

## 5. Comprobaciones pendientes

| Revisión | Linux | Windows |
| --- | --- | --- |
| Nombre efectivo | Visible en el prompt. | Visible en `ipconfig /all`. |
| Dirección por DHCP | Acreditada por dirección dinámica y rutas. | Acreditada por `ipconfig /all`. |
| Consulta a DNS-N02 | Respuesta explícita a `R-N02`. | Falta prueba. |
| DNS usado por defecto | Falta comprobar el resolvedor efectivo. | Parámetro configurado visible; falta consulta. |
| Inicio de sesión FTP | Acreditado con `bchecker`. | Falta prueba. |
| Aplicación web | Captura de cliente sin identificación concluyente. | Captura de cliente sin identificación concluyente. |
| Usuario local `bchecker` | Falta comprobación específica. | Creación acreditada. |

Para cerrar la validación, identificar cada equipo antes de capturar las pruebas, comprobar el acceso al gateway y repetir DNS y acceso web en ambos. La comprobación de tarjetas en Isard permitirá contrastar que solo tienen la conexión interna prevista.
