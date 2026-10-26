# Servidor web W-N02 y aplicación de prueba

[Volver al proyecto](../README.md) · [Arquitectura](arquitectura.md) · [Archivos y procedencia](../config/web/README.md)

## 1. Objetivo y estado

W-N02 aloja una aplicación PHP servida con Apache. Su formulario solicita usuario y contraseña para acceder a un listado de equipamientos de educación. La aplicación apunta al servidor de base de datos de la Intranet.

**La instalación web y la prueba de navegador tienen evidencias. El acceso SSH a W-N02 sigue pendiente de acreditación.** El responsable asignado en el repositorio es Faiver.

| Parámetro | Valor documentado |
| --- | --- |
| Hostname | `W-N02`, visible en los prompts. |
| Red prevista | DMZ, ITB 2. |
| Interfaz | `enp1s0` |
| IPv4 | `192.168.10.10/24` |
| Puerta de enlace | `192.168.10.1` |
| DNS mostrado | `1.1.1.1` |
| Paquetes instalados | `apache2`, `php`, `php-mysql`. |
| Archivo de la aplicación | `/var/www/html/index.php` |
| Destino MySQL visible en PHP | `192.168.20.20` |
| Base de datos indicada en PHP | `equipaments` |

La captura de paquetes muestra PHP 8.1 y referencias Jammy, coherentes con Ubuntu 22.04. No se utiliza esa observación para asignar una versión exacta de sistema sin consultar `/etc/os-release`.

## 2. Usuario de revisión

Se creó la cuenta con directorio personal y shell Bash:

```bash
sudo useradd -m -s /bin/bash bchecker
```

![Creación del usuario bchecker en el servidor web](../imagenes/W-N02/01-creacion-usuario.png)

*Figura 1. Creación de la cuenta en la VM.*

Se consultó su registro y se asignó contraseña con `sudo passwd bchecker`:

![Registro del usuario bchecker](../imagenes/W-N02/02-comprobacion-usuario.png)

*Figura 2. La cuenta dispone de `/home/bchecker` y `/bin/bash`.*

![Asignación de contraseña del usuario](../imagenes/W-N02/03-contrasena-usuario.png)

*Figura 3. El sistema confirma la actualización de contraseña.*

Las credenciales de laboratorio figuran en el README. La cuenta local de Linux y la cuenta que acepta MySQL son identidades de servicios distintos; compartir un nombre o contraseña no las convierte en la misma cuenta.

## 3. Red y conectividad

El Netplan de `/etc/netplan/00-installer-config.yaml` configura dirección fija, ruta predeterminada y DNS. Se conserva en [netplan-web.yaml](../config/web/netplan-web.yaml).

```yaml
network:
  version: 2
  ethernets:
    enp1s0:
      dhcp4: false
      addresses: [192.168.10.10/24]
      routes:
        - to: default
          via: 192.168.10.1
      nameservers:
        addresses: [1.1.1.1]
```

![Netplan del servidor web](../imagenes/R-N02/07-red-servidor-web.png)

*Figura 4. Se reutiliza la captura ya incorporada al documentar el router, que pertenece a W-N02.*

Las pruebas existentes muestran respuestas a `192.168.10.1` y `8.8.8.8`:

![Conectividad de W-N02 con el gateway](../imagenes/R-N02/08-ping-gateway-dmz.png)

*Figura 5. Seis paquetes enviados y recibidos, con 0 % de pérdida, hacia el gateway.*

![Conectividad externa de W-N02](../imagenes/R-N02/09-ping-internet-desde-dmz.png)

*Figura 6. Dos paquetes enviados y recibidos, con 0 % de pérdida, hacia una dirección externa.*

Estas pruebas verifican conectividad IP en ese momento. La segunda captura utiliza una dirección numérica; no acredita resolución DNS de Google, aunque el texto original lo describa así.

## 4. Instalación de Apache y PHP

Se ejecutó:

```bash
sudo apt install apache2 php php-mysql
```

![Instalación de Apache PHP y el conector MySQL](../imagenes/W-N02/04-instalacion-apache-php.png)

*Figura 7. Instalación de los componentes del servidor y la extensión de PHP para MySQL.*

Apache atiende las peticiones HTTP, PHP ejecuta el código de la aplicación y `php-mysql` aporta el acceso de PHP a MySQL. El servicio se comprobó con:

```bash
sudo systemctl status apache2
```

![Estado del servicio Apache](../imagenes/W-N02/05-estado-apache.png)

*Figura 8. Apache aparece como `active (running)` y la unidad como `enabled`.*

La captura acredita el estado en ese momento; no es una prueba de reinicio. Referencia: [instalación de Apache en Ubuntu](https://ubuntu.com/server/docs/how-to/web-services/install-apache2/).

## 5. Despliegue de la aplicación

### 5.1. Sustitución de la página predeterminada

El documento muestra la retirada de la página inicial de Apache:

```bash
sudo rm /var/www/html/index.html
```

![Retirada de la página predeterminada de Apache](../imagenes/W-N02/06-retirada-pagina-predeterminada.png)

*Figura 9. Operación realizada durante la preparación del directorio web.*

Después se editó `/var/www/html/index.php`. Se conserva la ruta visible en la captura, corrigiendo la errata `/var/ww/html/` del texto del Word.

### 5.2. Código visible y relación con MySQL

![Fragmento de index.php visible en el editor](../imagenes/W-N02/07-fragmento-aplicacion-php.png)

*Figura 10. Inicio del código PHP; la captura no muestra el archivo completo.*

El fragmento permite observar:

- Inicio de sesión PHP con `session_start()`.
- Destino `$host = "192.168.20.20"` y base `$bd = "equipaments"`.
- Una acción de salida que destruye la sesión y redirige a `index.php`.
- Tratamiento del formulario mediante una petición `POST`.
- Intento de conexión con `mysqli` utilizando los campos `usuario` y `clave` del formulario.
- Comprobación de `connect_errno` y mensaje de credenciales incorrectas si la conexión falla.

El login visible utiliza las credenciales para intentar conectar con MySQL. No se observa una tabla independiente de usuarios de aplicación. La captura corta el resto de la implementación, por lo que no permite reconstruir fielmente la consulta de datos ni el HTML completo.

Se incorpora solo el inicio legible en [index.php.fragmento.txt](../config/web/evidencias/index.php.fragmento.txt), como evidencia de texto. **No es una aplicación completa para desplegar.** Falta añadir el `index.php` real obtenido de W-N02.

Referencias: [sesiones en PHP](https://www.php.net/manual/es/book.session.php) y [conexión mysqli](https://www.php.net/manual/es/mysqli.construct.php).

## 6. Prueba en navegador

### 6.1. Formulario de acceso

El Word indica el acceso desde un cliente a `http://192.168.10.10`. La barra del navegador muestra `192.168.10.10/index.php` y se carga el formulario:

![Formulario de acceso de la aplicación](../imagenes/W-N02/08-formulario-login.png)

*Figura 11. Página Equipamientos de educación con campos de usuario y contraseña.*

La captura demuestra que un navegador de cliente alcanza la página. No identifica el hostname del equipo que la abre.

### 6.2. Tabla después del login

El texto indica que se introdujeron las credenciales de laboratorio de `bchecker`. La captura posterior muestra el usuario y la tabla:

![Tabla de equipamientos después del inicio de sesión](../imagenes/W-N02/09-tabla-tras-login.png)

*Figura 12. Cabecera `Usuario: bchecker · 200 filas`, registros visibles, buscador y botón Salir.*

El resultado acredita la presentación del listado tras el acceso. La combinación del destino visible en PHP y los datos en pantalla es coherente con el funcionamiento de la integración con la base de datos; no reemplaza una exportación del código ni las comprobaciones directas en MySQL.

El indicador de 200 filas describe lo mostrado en la aplicación. No acredita que ese sea el total de registros de la base de datos. Tampoco se ven pruebas de búsqueda, cierre de sesión o rechazo de credenciales incorrectas.

## 7. SSH en W-N02

El proyecto asigna a W-N02 funciones web y SSH, pero este Word no aporta una sesión remota contra esa máquina, su `sshd_config` ni un estado del servicio SSH identificado como W-N02. Las capturas del bastión y de OpenSSH pertenecen al apartado de la VM de base de datos y no se trasladan como prueba del servidor web.

Para documentar esta parte quedan por registrar el paquete y servicio efectivos, la configuración relevante y una conexión de prueba. También falta acreditar la conexión SSH de W-N02 con GitHub si forma parte de la entrega. Referencia para completar el procedimiento: [OpenSSH en Ubuntu](https://ubuntu.com/server/docs/how-to/security/openssh-server/).

## 8. Evidencias pendientes

- [ ] Obtener el `index.php` completo y conservarlo como código fuente.
- [ ] Identificar el cliente usado en la prueba de navegador y repetir el acceso en Linux y Windows.
- [ ] Registrar la consulta SQL, los permisos del usuario y el esquema real de la base de datos.
- [ ] Probar credenciales incorrectas, búsqueda y cierre de sesión.
- [ ] Incorporar la configuración efectiva de Apache si difiere de la instalación predeterminada.
- [ ] Acreditar SSH en W-N02 y, si corresponde, su uso con GitHub.

Esta documentación describe lo observado en el Word y no aplica cambios en la VM.
