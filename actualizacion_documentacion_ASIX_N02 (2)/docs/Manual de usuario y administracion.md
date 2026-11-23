# Manual de usuario y administración de la infraestructura N02

[Volver al proyecto](../README.md) · [Arquitectura](arquitectura.md) · [Ficheros de configuración](<Ficheros de configuración.md>)

> **Destinatarios:** personas que utilizarán la aplicación y responsables que deban arrancar, mantener o recuperar los servicios. Este manual explica la infraestructura que figura en el repositorio. Los comandos de mantenimiento y copia de seguridad son **procedimientos propuestos**, no pruebas de que ya se hayan ejecutado. Antes de utilizarlos en una máquina real hay que comprobar sus nombres, rutas y permisos.

## 1. Qué ofrece la infraestructura

El proyecto está desplegado en **Isard** y separa tres redes: la exterior (`Default`), la DMZ (`192.168.10.0/24`, ITB 2) y la Intranet (`192.168.20.0/24`, ITB 1). El router comunica estos segmentos. La aplicación web muestra un listado de equipamientos de educación una vez iniciada la sesión y consulta una base de datos MySQL.

| Máquina | Dirección | Servicio o función | Red |
| --- | --- | --- | --- |
| `R-N02` | `192.168.10.1` / `192.168.20.1`; exterior por DHCP | Router, reenvío de tráfico y NAT | Exterior, DMZ e Intranet |
| `DNS-N02` | `192.168.20.10` | DNS y DHCP con `dnsmasq` | Intranet |
| `B-N02` (nombre previsto) | `192.168.20.20` (dirección de diseño) | MySQL, base `equipaments` | Intranet |
| `W-N02` | `192.168.10.10` | Apache, PHP y aplicación web | DMZ |
| `F-N02` | `192.168.10.11` | FTP con `vsftpd` | DMZ |
| `PCL-N02` | DHCP; observada `192.168.20.112` | Cliente Linux | Intranet |
| `PCW-N02` | DHCP; observada `192.168.20.148` | Cliente Windows | Intranet |

Las IP de los clientes pueden cambiar. En las capturas del servidor de base de datos aparece también `B-NCC`: hay que contrastar el *hostname* y la IP actuales antes de realizar operaciones sobre él. La conexión de PHP a `192.168.20.20` y una tabla visible en el navegador son evidencias de integración, pero no sustituyen una comprobación actual de MySQL.

## 2. Manual de la persona usuaria

### 2.1. Abrir la aplicación web

1. Enciende el cliente Linux o Windows y comprueba que está conectado a la Intranet **ITB 1**.
2. Abre un navegador y entra en **http://192.168.10.10/index.php**.
3. Introduce las credenciales de acceso facilitadas por la persona administradora. En el proyecto, el formulario utiliza las credenciales de MySQL; no existe una tabla independiente de usuarios web acreditada.
4. Tras el acceso aparece la lista de equipamientos de educación. Puedes consultar los registros y utilizar el buscador de la pantalla.
5. Al terminar, pulsa **Salir** para cerrar la sesión.

![Formulario de entrada a la aplicación](../imagenes/W-N02/08-formulario-login.png)

*Figura 1. Formulario de acceso a la aplicación alojada en W-N02.*

![Listado de equipamientos tras iniciar sesión](../imagenes/W-N02/09-tabla-tras-login.png)

*Figura 2. Vista del listado con el usuario conectado, el buscador y la opción Salir. La captura muestra 200 filas; no se interpreta como el total de registros de la base de datos.*

**Importante:** la URL utiliza HTTP, no HTTPS. Es adecuada únicamente para el entorno de prácticas controlado; no se deben introducir contraseñas de uso personal ni exponerla a Internet tal como está.

### 2.2. Conectarse al servidor FTP desde Linux

Desde `PCL-N02` abre un terminal y ejecuta:

```bash
ftp 192.168.10.11
```

Introduce el usuario autorizado y la contraseña cuando se soliciten. Para consultar los archivos, descargar o subir un archivo **de prueba y con permiso**, puedes usar:

```text
ls
get archivo-prueba.txt
put archivo-prueba.txt
bye
```

`get` descarga al directorio local desde el que se abrió el programa y `put` envía un archivo local al directorio remoto. `bye` finaliza la sesión. **No ejecutes `put` o `get` hasta confirmar que el directorio y el archivo existen y que tienes autorización.**

![Conexión al FTP desde PCL-N02](../imagenes/F-N02/08-login-ftp-desde-linux.png)

*Figura 3. Acceso al servidor FTP con respuesta `230 Login successful`. Esta evidencia confirma el inicio de sesión, pero todavía no una transferencia de archivos.*

FTP tradicional transmite las credenciales y los datos sin cifrar si no se ha configurado TLS. No debe utilizarse para información sensible ni sobre redes no confiables. El repositorio no contiene una configuración TLS verificada.

### 2.3. Verificar la red desde los clientes

En Linux:

```bash
ip -br address
ip route
nslookup R 192.168.20.10
ping -c 4 192.168.20.1
```

En Windows, desde CMD:

```cmd
ipconfig /all
nslookup R 192.168.20.10
ping 192.168.20.1
```

La configuración esperada para los clientes es una IP entre `192.168.20.100` y `192.168.20.150`, puerta de enlace `192.168.20.1` y servidor DNS/DHCP `192.168.20.10`. Si falla alguna comprobación, informa a administración; no cambies una IP o un DNS al azar.

## 3. Manual de administración

### 3.1. Acceso y precauciones

Las máquinas están en Isard. Entra con tu cuenta autorizada, localiza la VM por su nombre y abre la consola. Si el acceso SSH está instalado, permitido por la red y habilitado, también puedes conectarte desde otro equipo con alcance a su IP:

```bash
ssh USUARIO_ADMIN@192.168.20.10  # Ejemplo: DNS-N02
ssh USUARIO_ADMIN@192.168.10.10  # Ejemplo: W-N02; comprobar SSH primero
```

Sustituye `USUARIO_ADMIN` por una cuenta con los permisos adecuados, sin escribir contraseñas en el comando. Desde fuera de las redes internas puede ser necesario el bastión de Isard; la documentación del proyecto menciona su uso durante la preparación de B-N02, **no** acredita un SSH operativo desde cualquier origen a todas las VMs. La cuenta `bchecker` se creó para revisión; no se ha demostrado que tenga permisos `sudo` en todos los equipos.

Antes de intervenir: anota VM, hora, motivo, estado actual y resultado; guarda una copia de los archivos que vayas a modificar; comprueba que otra persona no está trabajando en el mismo servicio. No modifiques Netplan por SSH sin acceso alternativo a la consola, porque podrías perder la conexión.

### 3.2. Orden recomendado de encendido

En el panel de Isard, selecciona cada máquina y utiliza la opción de **iniciar/encender** que ofrezca tu interfaz. Espera a que termine de arrancar y comprueba su estado antes de pasar a la siguiente:

1. **R-N02:** primero debe enrutar entre redes.
2. **DNS-N02:** después inicia DNS y DHCP para los clientes.
3. **B-N02:** comprueba MySQL antes de iniciar las pruebas de la aplicación.
4. **W-N02 y F-N02:** inicia web y FTP.
5. **PCL-N02 y PCW-N02:** arranca los clientes y deja que obtengan DHCP.

El orden es una recomendación operativa para evitar fallos temporales por dependencias; no significa que los servicios se hayan configurado para arrancar en cadena. Para apagar todo el entorno, primero cierra las sesiones, apaga los clientes y servicios de aplicación y deja **R-N02** para el final.

Para apagar o reiniciar una VM Linux de forma ordenada, desde una sesión autorizada:

```bash
sudo shutdown -h now  # Apagado ordenado
sudo reboot           # Reinicio ordenado
```

Si Isard dispone de una opción para apagar o reiniciar de forma controlada, también puede utilizarse. Evita el apagado forzado salvo bloqueo confirmado y después de evaluar la posibilidad de pérdida de datos. **Reiniciar una VM es distinto de reiniciar un servicio.**

### 3.3. Consultar el estado y controlar los servicios

Ejecuta los comandos **en la máquina indicada**, no desde cualquiera de los equipos:

| Equipo | Consultar estado | Iniciar / reiniciar servicio |
| --- | --- | --- |
| `DNS-N02` | `sudo systemctl status dnsmasq` | `sudo systemctl start dnsmasq` / `sudo systemctl restart dnsmasq` |
| `W-N02` | `sudo systemctl status apache2` | `sudo systemctl start apache2` / `sudo systemctl restart apache2` |
| `F-N02` | `sudo systemctl status vsftpd` | `sudo systemctl start vsftpd` / `sudo systemctl restart vsftpd` |
| `B-N02` | `sudo systemctl status mysql` | `sudo systemctl start mysql` / `sudo systemctl restart mysql` **solo tras confirmar el nombre de la unidad** |
| VM con SSH | `sudo systemctl status ssh` | `sudo systemctl restart ssh` **solo si está instalado y configurado** |

Para detener un servicio existe `sudo systemctl stop NOMBRE`. Para comprobar si arranca con el sistema, `systemctl is-enabled NOMBRE`; si hace falta habilitarlo, `sudo systemctl enable NOMBRE` después de revisar la configuración. **No detengas dnsmasq, MySQL o el router mientras haya usuarios trabajando sin avisar.**

Si un servicio falla:

```bash
sudo systemctl --failed
sudo journalctl -u NOMBRE -n 50 --no-pager
sudo ss -lntup
```

Sustituye `NOMBRE` por la unidad concreta (por ejemplo, `apache2`). Los registros y la salida de `ss` ayudan a ver errores y puertos abiertos. No pruebes a reiniciar todo el sistema como primer paso.

### 3.4. Router R-N02

```bash
ip -br address
ip route
sysctl net.ipv4.ip_forward
sudo iptables -t nat -S POSTROUTING
sudo systemctl status netfilter-persistent
```

Se espera encontrar las interfaces `enp1s0` (exterior por DHCP), `enp2s0` (`192.168.10.1/24`) y `enp3s0` (`192.168.20.1/24`), el reenvío IPv4 a `1` y una regla NAT `MASQUERADE` hacia `enp1s0`. El repositorio documenta que se instaló `iptables-persistent`, pero **no contiene una exportación completa verificada** de `/etc/iptables/rules.v4`. Antes de modificar reglas, guarda el estado actual. Más información en [Router R-N02](router.md).

### 3.5. DNS y DHCP en DNS-N02

```bash
sudo dnsmasq --test
sudo systemctl status dnsmasq
nslookup R 192.168.20.10
nslookup google.com 192.168.20.10
```

`dnsmasq --test` debe terminar sin errores de sintaxis. El fichero principal es `/etc/dnsmasq.conf`; el rango DHCP documentado es `192.168.20.100-192.168.20.150`, concesión de `12h`. Antes de cambiar el archivo, comprueba si el registro del router usa `R`, `R-NCC` o `R-N02`: hay diferencias entre una captura y una consulta posterior. Consulta [DNS y DHCP](<DNS y DHCP.md>) y su [inventario](../config/dns-dhcp/README.md).

### 3.6. Aplicación web y base de datos

En W-N02:

```bash
sudo systemctl status apache2
php -v
ls -l /var/www/html/
```

La aplicación principal se encuentra en `/var/www/html/index.php`, pero **el repositorio conserva solo un fragmento** de su código y no sirve para reinstalarla completa. En B-N02, después de confirmar su acceso y que usa MySQL:

```bash
sudo systemctl status mysql
sudo mysql -e 'SHOW DATABASES;'
sudo mysql -e 'SHOW TABLES FROM equipaments;'
```

`SHOW TABLES` permite conocer el nombre efectivo antes de consultar o modificar registros. No se debe deducir que la tabla esté completa únicamente porque el navegador muestre 200 filas. Si el acceso al navegador funciona pero el login no, verifica primero conectividad de W-N02 a B-N02 y el servicio MySQL; los permisos de usuario y la configuración de escucha siguen pendientes de inventariar. Más información en [Web](<Servidor web, SSH y aplicación de prueba.md>) y [Base de datos](<Base de datos.md>).

### 3.7. Servidor FTP F-N02

```bash
sudo systemctl status vsftpd
sudo grep -E '^(anonymous_enable|local_enable|write_enable|local_root|chroot_local_user)' /etc/vsftpd.conf
```

Las tres directivas que aparecen en la evidencia son `anonymous_enable=NO`, `local_enable=YES` y `write_enable=YES`. No se ha documentado todo el `vsftpd.conf`, el directorio real de almacenamiento, el cifrado ni el modo pasivo. **No sustituyas el archivo real por el fragmento del repositorio.** Consulta [Servidor FTP](<Servidor FTP.md>).

## 4. Copias de seguridad y restauración

### 4.1. Situación actual y política recomendada

**En la documentación actual no consta una solución automática de copias de seguridad ni una restauración probada.** Los siguientes procedimientos son una guía para implantar y ensayar, no un resultado ya validado.

Como mínimo conviene conservar: (a) configuraciones de red y servicios de cada servidor; (b) el código web **completo**; (c) un volcado consistente de MySQL; (d) los archivos reales del FTP, una vez localizada su ruta; y (e) una copia exportada fuera de las VMs. Una instantánea de Isard, si la plataforma y los permisos la permiten, puede servir como punto de retorno antes de un cambio, pero no sustituye a una copia externa verificable.

Una propuesta de frecuencia es **diaria para la base de datos**, **antes de cada cambio para configuración y código** y **una prueba periódica de recuperación**. El responsable debe confirmar retención, ubicación, espacio y protección de los ficheros. No guardes contraseñas ni copias sensibles en un repositorio público.

### 4.2. Crear copias de configuración

En **cada VM Linux** crea primero una ubicación protegida y fija una marca temporal:

```bash
FECHA=$(date +%Y%m%d-%H%M%S)
sudo install -d -m 700 /var/backups/asix
```

Después, ejecuta **solo el comando correspondiente al servidor**, una vez comprobado que existen esos ficheros:

**R-N02:**

```bash
sudo tar -C / -czf "/var/backups/asix/router-$FECHA.tar.gz" etc/netplan etc/sysctl.conf etc/iptables/rules.v4
```

**DNS-N02:**

```bash
sudo tar -C / -czf "/var/backups/asix/dns-$FECHA.tar.gz" etc/netplan etc/dnsmasq.conf
```

**W-N02:**

```bash
sudo tar -C / -czf "/var/backups/asix/web-$FECHA.tar.gz" etc/netplan etc/apache2 var/www/html
```

**F-N02:**

```bash
sudo tar -C / -czf "/var/backups/asix/ftp-config-$FECHA.tar.gz" etc/netplan etc/vsftpd.conf
```

En F-N02 falta identificar la carpeta de datos FTP; las copias anteriores guardan **configuración**, no necesariamente los archivos transferidos. En B-N02 también falta inventariar las rutas del sistema. Antes de dar una copia por buena:

```bash
sudo tar -tzf /var/backups/asix/NOMBRE-ARCHIVO.tar.gz
```

Sustituye `NOMBRE-ARCHIVO` por el archivo generado y comprueba que contiene lo previsto. Copia los respaldos fuera de la VM con un método autorizado y comprueba que se pueden volver a leer: perder el disco de la VM también haría perder una copia local.

### 4.3. Copiar la base de datos MySQL

En **B-N02**, después de confirmar que MySQL, la base `equipaments` y `mysqldump` están disponibles y de utilizar una cuenta autorizada:

```bash
umask 077
mkdir -p "$HOME/copias-asix"
FECHA=$(date +%Y%m%d-%H%M%S)
sudo mysqldump --single-transaction --routines --triggers --databases equipaments > "$HOME/copias-asix/equipaments-$FECHA.sql"
```

Comprueba que el comando termina correctamente y que el archivo **no está vacío**. `--single-transaction` es apropiado para tablas transaccionales como InnoDB; hay que comprobar el motor real y tratar aparte cualquier contenido no transaccional. El volcado no sustituye a una copia de usuarios, permisos ni configuración de MySQL. Guarda el resultado en un destino externo protegido.

### 4.4. Restaurar una copia de configuración

1. Anota qué servicio falla y cuál es la última copia válida. Avisa a las personas usuarias y realiza un respaldo del estado actual antes de sobrescribir nada.
2. Lleva la copia confiable a la **VM correcta**. Inspecciona el contenido con `tar -tzf`; no extraigas archivos de procedencia desconocida como administrador.
3. Si la copia procede de los comandos anteriores, recupera **solo los ficheros necesarios**, no todos sin revisar. Por ejemplo, para revisar el `dnsmasq.conf` restaurado en una carpeta temporal de DNS-N02:

```bash
mkdir -p "$HOME/revision-restauracion"
tar -xzf /RUTA/AL/dns-AAAAMMDD-HHMMSS.tar.gz -C "$HOME/revision-restauracion" etc/dnsmasq.conf
```

4. Compara `~/revision-restauracion/etc/dnsmasq.conf` con el archivo actual. Si decides recuperarlo, crea una copia adicional del actual y traslada el contenido con los permisos adecuados. Antes de reiniciar ejecuta `sudo dnsmasq --test`.
5. Reinicia **solo** el servicio afectado y valida de nuevo las consultas DNS, DHCP o conectividad que correspondan. Si la restauración afecta a Netplan o reglas del router, utiliza la consola de Isard y un plan de reversión: podrías dejar el servidor sin red.

No se incluye un comando de sobrescritura masiva, porque cada servidor tiene una configuración real que debe contrastarse y la reposición incorrecta puede cortar el acceso.

### 4.5. Restaurar MySQL

**Es una operación con riesgo de sobrescribir datos.** Comprueba primero la copia en una instancia o base de pruebas y acuerda una ventana de mantenimiento. Después de guardar una copia nueva del estado actual, y **solo cuando esté aprobado**, una exportación generada con `--databases equipaments` se puede importar en B-N02 de esta forma:

```bash
sudo mysql < /RUTA/AL/equipaments-AAAAMMDD-HHMMSS.sql
```

La importación puede contener órdenes para crear o modificar tablas y datos. Comprueba el contenido SQL antes de ejecutarla. Tras restaurar, consulta `SHOW TABLES FROM equipaments;`, valida las filas necesarias y prueba de nuevo el acceso web. Esta recuperación está **pendiente de ensayo** en el proyecto.

### 4.6. Recuperar una VM completa

Si Isard ofrece **instantáneas, exportación o restauración** para estas VMs y el usuario tiene permisos, sigue el procedimiento oficial de la plataforma y asegúrate de identificar el equipo correcto. Antes de volver a un estado anterior, ten en cuenta que se pueden perder datos posteriores a la instantánea. Tras recuperarlo, revisa el hostname, tarjetas de red, direcciones IP, rutas, NAT, servicios y prueba la aplicación de extremo a extremo. En este repositorio **no hay una exportación verificada de las VMs** ni capturas de esa restauración.

## 5. Diagnóstico rápido de problemas

| Síntoma | Comprobaciones por orden |
| --- | --- |
| El cliente no recibe IP | Comprueba ITB 1 en Isard, `ipconfig /all` o `ip a`, y el servicio `dnsmasq` en DNS-N02. |
| Hay IP pero no conecta con la DMZ | Prueba `ping 192.168.20.1`, revisa R-N02, `ip_forward` y las rutas. |
| No se resuelven nombres | Ejecuta `nslookup R 192.168.20.10`; revisa el DNS recibido por DHCP y los registros de dnsmasq. |
| La web no abre | Prueba `http://192.168.10.10/index.php`; revisa conectividad y `systemctl status apache2` en W-N02. |
| El formulario abre, pero falla el acceso | Revisa servicio MySQL, IP `192.168.20.20`, credenciales, permisos y logs; no publiques contraseñas. |
| El FTP no conecta | Comprueba `192.168.10.11`, estado de `vsftpd`, cortafuegos y ruta a través de R-N02. |
| FTP conecta, pero no permite transferir | Comprueba permisos del directorio del usuario y configuración completa de `vsftpd` antes de cambiarla. |
| Internet no funciona desde DMZ/Intranet | Comprueba ruta por defecto hacia R-N02, `ip_forward`, NAT en `enp1s0` y conectividad de la interfaz exterior. |

Después de una reparación registra fecha, máquina, causa, acción realizada, resultado y evidencia. Si la incidencia se repite, documenta el procedimiento validado en el apartado técnico correspondiente.

## 6. Seguridad y mantenimiento

- Mantén separadas las cuentas del sistema operativo, las de MySQL y las de acceso a Isard. Entrega credenciales por un canal seguro, no en GitHub.
- **El README actual publica una contraseña de laboratorio.** Debe retirarse de la documentación pública y cambiarse donde esté en uso antes de considerar esta infraestructura segura.
- Utiliza el mínimo privilegio necesario para las operaciones. La existencia de `bchecker` no implica permisos de administración.
- Antes de actualizar, cambiar Netplan o configurar cortafuegos, guarda copia y comprueba un acceso alternativo por consola.
- El acceso por HTTP y FTP sin cifrado debe mantenerse restringido al laboratorio; para un uso real sería necesario revisar HTTPS, FTPS/SFTP, filtrado y credenciales.
- Después de cambiar un servicio, verifica su estado y realiza una prueba funcional desde un cliente, no solo `systemctl status`.

## 7. Responsables, evidencias y tareas pendientes

Según el reparto que figura en el README: **Elian** (router, planificación en ProofHub y diagrama), **Daniel** (GitHub, DNS y DHCP), **Faiver** (web, SSH, clientes y aplicación) y **Elias** (base de datos y FTP). Este reparto es una referencia para trasladar incidencias; no sustituye a la persona administradora que tenga permisos en Isard.

| Falta verificar para cerrar el manual | Evidencia que convendría añadir |
| --- | --- |
| Nombre, IP y unidad MySQL de B-N02 | `hostname`, `ip -br a`, `systemctl status mysql` y salida controlada de `SHOW TABLES`. |
| Inicio remoto SSH en W-N02 y otros equipos | Prueba de conexión desde un origen permitido, sin mostrar secretos. |
| Configuración real de Apache, PHP y MySQL | Inventario y copias completas de los ficheros efectivos; no solo fragmentos. |
| Transferencia FTP y seguridad | `get` y `put` de un archivo de prueba, ruta de datos y confirmación de si hay cifrado. |
| Copias de seguridad y recuperación | Archivo creado, verificación de integridad, almacenamiento externo y restauración de prueba. |
| Persistencia y alcance de la infraestructura | Repetir pruebas después de reiniciar las VMs y comprobar reglas entre DMZ e Intranet. |

Para ampliar cada paso, utiliza la [documentación técnica](../README.md#índice-de-documentación). Este manual no reemplaza los procedimientos del administrador de Isard ni las políticas de copias de seguridad de la organización.
