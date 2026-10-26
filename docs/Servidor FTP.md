# Servidor FTP F-N02

[Volver al proyecto](../README.md) · [Arquitectura](arquitectura.md) · [Archivos de configuración](../config/ftp/README.md)

## 1. Objetivo y entorno

F-N02 proporciona el servicio de transferencia de archivos del proyecto mediante **vsftpd**. Está situado en la DMZ, junto al servidor web, y utiliza R-N02 para acceder a otras redes.

| Parámetro | Valor documentado |
| --- | --- |
| Hostname | `F-N02` |
| Red de Isard prevista | ITB 2 |
| Segmento | `192.168.10.0/24` |
| Interfaz del servidor | `enp1s0` |
| Dirección fija | `192.168.10.11/24` |
| Puerta de enlace | `192.168.10.1` |
| DNS mostrado | `1.1.1.1` |
| Software observado | vsftpd `3.0.5` |

La instalación utiliza paquetes del repositorio Ubuntu Jammy, correspondiente a Ubuntu 22.04. La edición y versión exactas de la VM se deben confirmar con `/etc/os-release`. El reparto inicial asigna FTP a Elias; las nuevas capturas aparecen en la sección de Elian del Word. Esta memoria conserva el reparto del repositorio sin deducir un cambio formal de responsable.

## 2. Preparación de la máquina

### 2.1. Usuario y nombre

Se creó el usuario de revisión mediante:

```bash
sudo adduser bchecker
```

![Creación del usuario bchecker en F-N02](../imagenes/F-N02/01-creacion-usuario-bchecker.png)

*Figura 1. Creación de la cuenta local y asignación de contraseña.*

La comprobación del nombre devuelve `F-N02`:

```bash
hostname
```

![Nombre del servidor FTP](../imagenes/F-N02/02-hostname-ftp.png)

*Figura 2. Hostname efectivo del servidor.*

El Word indica que se cambió el nombre, pero no muestra el comando utilizado. Para reproducir esa asignación se puede usar `sudo hostnamectl set-hostname F-N02`; se distingue esta instrucción de la comprobación capturada.

### 2.2. Red estática

La captura de `/etc/netplan/00-installer-config.yaml` contiene estas directivas, conservadas en [netplan-ftp.yaml](../config/ftp/netplan-ftp.yaml):

```yaml
network:
  version: 2
  ethernets:
    enp1s0:
      addresses: [192.168.10.11/24]
      routes:
        - to: default
          via: 192.168.10.1
      nameservers:
        addresses: [1.1.1.1]
```

![Configuración Netplan del servidor FTP](../imagenes/F-N02/03-netplan-ftp.png)

*Figura 3. Dirección fija y ruta predeterminada por la interfaz DMZ de R-N02.*

La dirección fija facilita que los clientes encuentren siempre el servicio en la misma IP. El DNS mostrado es externo; no se sustituye por DNS-N02 en esta transcripción, porque ese cambio no aparece en la evidencia.

### 2.3. Conectividad previa

```bash
ping 192.168.10.1
ping 8.8.8.8
```

![Pruebas de conectividad de F-N02](../imagenes/F-N02/04-conectividad-router-internet.png)

*Figura 4. Dos paquetes enviados y recibidos, con 0 % de pérdida, en cada destino.*

Las respuestas acreditan acceso al gateway y a una dirección externa desde F-N02. Al utilizar direcciones IP, estas pruebas no comprueban la resolución DNS. La ruta exclusiva por el router se debe contrastar con las tarjetas efectivas de Isard y la tabla de rutas.

## 3. Instalación del servicio

Se instaló el paquete mediante:

```bash
sudo apt install vsftpd
```

![Instalación de vsftpd](../imagenes/F-N02/05-instalacion-vsftpd.png)

*Figura 5. Descarga de vsftpd y sus dependencias desde los repositorios Jammy.*

Después se consultó el estado:

```bash
sudo systemctl status vsftpd
```

![Estado activo de vsftpd](../imagenes/F-N02/06-estado-vsftpd.png)

*Figura 6. Servicio `active (running)` y unidad marcada como `enabled`.*

Esta salida acredita que el proceso está activo y la unidad tiene el arranque habilitado en ese momento. No sustituye una prueba de funcionamiento después de reiniciar la VM. Además, la captura precede a la edición de opciones en el orden del documento.

## 4. Configuración de acceso y escritura

En `/etc/vsftpd.conf` se muestran:

```ini
anonymous_enable=NO
local_enable=YES
write_enable=YES
```

![Directivas visibles de vsftpd](../imagenes/F-N02/07-directivas-vsftpd.png)

*Figura 7. Acceso anónimo deshabilitado, usuarios locales habilitados y órdenes de escritura permitidas.*

| Directiva | Efecto |
| --- | --- |
| `anonymous_enable=NO` | Deshabilita el inicio de sesión anónimo. |
| `local_enable=YES` | Permite autenticación con cuentas locales, como `bchecker`, sujeta al resto de controles del servicio. |
| `write_enable=YES` | Habilita órdenes de escritura; la operación concreta también depende de los permisos del sistema de archivos. |

Se conserva únicamente este fragmento en [vsftpd-fragmento.conf](../config/ftp/vsftpd-fragmento.conf). No acredita la configuración completa de rutas, confinamiento de usuarios, TLS o puertos de datos.

Para reproducir un cambio del archivo hay que guardarlo y reiniciar el servicio, comprobando después su estado. Esa ejecución posterior no se ve en el Word:

```bash
sudo systemctl restart vsftpd
sudo systemctl status vsftpd
```

Referencia: [manual de vsftpd.conf para Ubuntu Jammy](https://manpages.ubuntu.com/manpages/jammy/man5/vsftpd.conf.5.html).

## 5. Prueba de acceso desde Linux

Desde una terminal identificada como **PCL-N02**, se abrió una conexión:

```bash
ftp 192.168.10.11
```

Se introdujo el usuario `bchecker` y la contraseña de laboratorio. El servidor devolvió:

```text
220 (vsFTPd 3.0.5)
230 Login successful.
```

![Sesión FTP autenticada desde PCL-N02](../imagenes/F-N02/08-login-ftp-desde-linux.png)

*Figura 8. Autenticación correcta y presentación del prompt `ftp>`.*

La prueba acredita conectividad con el servicio y autenticación de la cuenta. En la arquitectura prevista, el cliente está en la Intranet y F-N02 en la DMZ, por lo que la comunicación requiere enrutamiento entre ambos segmentos.

FTP utiliza conexiones separadas para el control de sesión y los datos. **Un login correcto no demuestra una subida o descarga correcta.** La captura no muestra `ls`, `put`, `get` ni un resultado de transferencia; tampoco permite determinar si se utilizó modo activo o pasivo.

Referencia: [protocolo FTP, RFC 959](https://www.rfc-editor.org/rfc/rfc959).

## 6. Validación pendiente

| Comprobación | Situación |
| --- | --- |
| Hostname F-N02 | Acreditado. |
| IP, gateway y DNS en Netplan | Configuración visible; falta exportación efectiva y tabla de rutas. |
| Ping al gateway y a una IP externa | Acreditados. |
| Instalación y estado de vsftpd | Acreditados. |
| Inicio de sesión desde PCL-N02 | Acreditado con `bchecker`. |
| Listado de directorios | No aparece. |
| Subida y descarga | No aparecen. |
| Acceso desde Windows | No aparece. |
| Configuración completa y persistencia | Faltan copia del archivo efectivo y prueba tras reinicio. |

Para completar la prueba funcional se propone crear en el cliente un archivo de texto propio, subirlo con `put`, recuperarlo con otro nombre mediante `get` y comparar su contenido. Deben registrarse el directorio utilizado, sus permisos y el resultado de cada operación. Estas acciones quedan pendientes; no se dan por realizadas.
