# Servidor DNS y DHCP

[Volver al índice del proyecto](../README.md)

## 1. Objetivo

El servidor **DNS-N02** da los servicios DNS y DHCP a los equipos de la Intranet del proyecto. Los dos servicios los hice con **dnsmasq** en la misma máquina:

- **DNS:** resuelve los nombres internos y manda las consultas de nombres de fuera al servidor `1.1.1.1`.
- **DHCP:** da automáticamente a los clientes una IP, la máscara, la puerta de enlace y el servidor DNS.

En esta documentación explico cómo preparé el servidor, cómo lo configuré y las pruebas que hice desde los clientes Linux y Windows. Las capturas son del documento de la práctica.

Los archivos de configuración están por separado en [config/dns-dhcp](../config/dns-dhcp/README.md). Aquí explico qué hice, por qué puse esos valores y qué resultados dieron las pruebas. En el [inventario de configuración](<Ficheros de configuración.md>) se ve dónde va cada archivo en cada máquina.

### 1.1. Cómo funciona y por qué lo hice así

El DNS sirve para usar nombres en vez de tener que acordarse de las IPs. En este proyecto, dnsmasq contesta con los nombres locales que conoce y, si no los conoce, pregunta a `1.1.1.1`. Las pruebas comprueban consultas internas y externas.

El DHCP evita tener que configurar a mano cada cliente. Cuando un equipo pide una IP por primera vez se intercambian cuatro mensajes: **DHCPDISCOVER** para buscar un servidor, **DHCPOFFER** para ofrecer una IP, **DHCPREQUEST** para pedirla y **DHCPACK** para confirmarla. Esto es la teoría del protocolo; no hice captura de esos mensajes.

La concesión dura 12 horas, y pasado ese tiempo el cliente tiene que renovarla. Las IPs que salen en las pruebas son dinámicas, no reservadas. El rango tiene 51 direcciones y deja fuera las IPs fijas del router, del servidor DNS/DHCP y de la base de datos.

Usé dnsmasq porque hace las dos cosas con un solo programa y un solo archivo de configuración. La IP fija es para que los clientes siempre encuentren el servidor, la puerta de enlace es para que salgan a otras redes, y la opción DNS del DHCP hace que los clientes pregunten a nuestro servidor. Si solo les diera un DNS de fuera, no podrían resolver los nombres internos, que solo existen en este servidor.

## 2. Parámetros de red

| Parámetro | Valor |
| --- | --- |
| Nombre del servidor | `DNS-N02` |
| Red de Isard | `ITB 1` (Intranet) |
| Interfaz del servidor | `enp1s0` |
| Subred | `192.168.20.0/24` |
| Máscara | `255.255.255.0` |
| IP fija del servidor | `192.168.20.10` |
| Puerta de enlace | `192.168.20.1` (`R-N02`) |
| DNS externo de reenvío | `1.1.1.1` |
| Rango DHCP | `192.168.20.100` a `192.168.20.150`, ambos incluidos |
| Duración de la concesión | `12h` |
| DNS entregado a los clientes | `192.168.20.10` |

El servidor y los clientes tienen una sola tarjeta de red, conectada a `ITB 1`. La salida a internet es a través del router del proyecto. La IP fija del servidor está fuera del rango DHCP para que no se la asigne a ningún cliente.

## 3. Preparación del servidor

### 3.1. Usuario y herramientas

El proyecto necesita el usuario `bchecker`. Si no existe, se crea con:

```bash
sudo adduser bchecker
```

En la captura del documento original se ve que el usuario ya existía al intentar crearlo otra vez, así que no hay que repetirlo.

Después actualicé los paquetes e instalé el servicio SSH y el editor de texto:

```bash
sudo apt update
sudo apt install openssh-server nano
```

### 3.2. Nombre del equipo

Le puse el nombre que tiene en el esquema del proyecto:

```bash
sudo hostnamectl set-hostname DNS-N02
```

![Asignación del hostname DNS-N02 mediante hostnamectl](<../imagenes/DNS i DHCP/01-hostname-servidor.png>)

*Figura 1. Cambio del nombre del servidor a DNS-N02.*

### 3.3. Dirección IP fija

El servidor necesita una IP que no cambie para que los clientes lo encuentren. Primero miré la interfaz y los archivos de Netplan:

```bash
ip a
ls /etc/netplan/
sudo nano /etc/netplan/00-installer-config.yaml
```

En [netplan-servidor-captura.yaml.txt](../config/dns-dhcp/evidencias/netplan-servidor-captura.yaml.txt) está lo que se ve en la captura original. En [netplan-servidor.yaml](../config/dns-dhcp/netplan-servidor.yaml) está el archivo completo, con la puerta de enlace que en la captura faltaba.

Como DNS del propio servidor puse `1.1.1.1` mientras lo preparaba. Es independiente del DNS `192.168.20.10` que el DHCP da a los clientes.

![Archivo Netplan del servidor con la dirección estática 192.168.20.10/24](<../imagenes/DNS i DHCP/02-netplan-servidor.png>)

*Figura 2. Captura original de Netplan. Se ve la IP de la Intranet, pero la ruta por defecto está incompleta: falta `via: 192.168.20.1`.*

> **Corrección del documento original:** a DNS-N02 le corresponden la IP `192.168.20.10/24` y la puerta de enlace `192.168.20.1`. Las direcciones `192.168.10.10` y `192.168.10.1` son de la DMZ y no se deben usar en este servidor. El archivo de `config/dns-dhcp` ya lleva la ruta completa.

Para comprobar el archivo y aplicar los cambios:

```bash
sudo netplan generate
sudo netplan apply
```

## 4. Instalación de DNS y DHCP

Instalé dnsmasq y las herramientas para hacer consultas DNS:

```bash
sudo apt install dnsmasq dnsutils
dnsmasq --version
```

`dnsmasq` da los dos servicios y `dnsutils` trae `nslookup` y `dig`, que sirven para probar que se resuelven los nombres.

![Salida de dnsmasq --version con la versión 2.91](<../imagenes/DNS i DHCP/03-version-dnsmasq.png>)

*Figura 3. Comprobación de la instalación. En la captura sale dnsmasq 2.91.*

## 5. Configuración de dnsmasq

Los dos servicios se configuran en el mismo archivo:

```bash
sudo nano /etc/dnsmasq.conf
```

Las líneas que se ven en la captura están guardadas en [config/dns-dhcp/dnsmasq.conf](../config/dns-dhcp/dnsmasq.conf). Abajo explico qué hace cada una.

![Configuración conjunta de DNS y DHCP en /etc/dnsmasq.conf](<../imagenes/DNS i DHCP/04-configuracion-dnsmasq.png>)

*Figura 4. Configuración original de dnsmasq con el rango, la puerta de enlace y el DNS de los clientes.*

| Directiva | Función |
| --- | --- |
| `interface=enp1s0` | Elige la interfaz de la Intranet para que dnsmasq escuche ahí. |
| `bind-interfaces` | Hace que solo escuche en las interfaces elegidas. |
| `no-resolv` | No coge los servidores DNS de `/etc/resolv.conf`. |
| `server=1.1.1.1` | Pone el servidor DNS de fuera al que se reenvían las consultas. |
| `host-record=R-NCC,R,192.168.20.1` | Asocia los nombres internos `R-NCC` y `R` con la IP del router. |
| `dhcp-range=192.168.20.100,192.168.20.150,12h` | Define el rango de IPs y la duración de la concesión. |
| `dhcp-option=option:router,192.168.20.1` | Da la puerta de enlace a los clientes. |
| `dhcp-option=option:dns-server,192.168.20.10` | Da el DNS local a los clientes. |

### 5.1. Nombre del router

En el esquema del proyecto el router se llama `R-N02`, pero en la captura de dnsmasq los nombres son `R-NCC` y `R`. En una prueba posterior desde Linux sí se resuelve `R-N02`, pero no tengo la configuración que lo permite.

Por eso hay una [versión de dnsmasq.conf](../config/dns-dhcp/propuestas/dnsmasq.conf) que añade también `R-N02` al registro del router. Así siguen funcionando los nombres de la captura y además se resuelve `R-N02`. Esta versión hay que probarla en la máquina antes de darla por buena.

## 6. Inicio y comprobación del servicio

Después de guardar la configuración reinicié dnsmasq, lo habilité para que arranque solo y miré su estado:

```bash
sudo systemctl restart dnsmasq
sudo systemctl enable dnsmasq
sudo systemctl status dnsmasq
```

![Estado de dnsmasq con el servicio habilitado y active (running)](<../imagenes/DNS i DHCP/05-estado-servicio.png>)

*Figura 5. dnsmasq aparece habilitado y en estado `active (running)`.*

Cada vez que se edite el archivo conviene ejecutar esto **antes de reiniciar**, para ver si hay algún error de sintaxis:

```bash
sudo dnsmasq --test
```

## 7. Pruebas de DNS

### 7.1. Nombre interno y nombre externo desde el servidor

Hice las consultas directamente al DNS local:

```bash
nslookup R 192.168.20.10
nslookup google.com 192.168.20.10
```

La primera devuelve `192.168.20.1`, que es la IP del router en la Intranet. La segunda devuelve direcciones de `google.com`, así que dnsmasq también resuelve nombres de fuera. Las IPs de un dominio público pueden cambiar de una consulta a otra.

![Consultas nslookup del nombre R y de google.com al servidor 192.168.20.10](<../imagenes/DNS i DHCP/06-pruebas-dns-servidor.png>)

*Figura 6. Resolución correcta de un nombre interno y de un dominio externo.*

## 8. Pruebas desde los clientes

Las pruebas de los clientes están aquí porque sirven para comprobar que el DNS y el DHCP funcionan desde otros equipos de la Intranet.

### 8.1. Cliente Linux: PCL-N02

El cliente se conecta a `ITB 1` y se activa DHCP para IPv4. Para eso edité `/etc/netplan/01-network-manager-all.yaml`. Lo que se ve del archivo está en [config/dns-dhcp/netplan-cliente-linux.yaml](../config/dns-dhcp/netplan-cliente-linux.yaml); con `dhcp4: true` el equipo pide la configuración de red automáticamente.

Después de guardar apliqué la configuración:

```bash
sudo netplan generate
sudo netplan apply
```

![Configuración del cliente Linux con dhcp4 activado en enp1s0](<../imagenes/DNS i DHCP/07-netplan-cliente-linux.png>)

*Figura 7. Configuración del cliente para obtener una IPv4 automáticamente.*

Para comprobarlo usé:

```bash
ip route
```

![Tabla de rutas de Linux con la IP 192.168.20.112 y la puerta de enlace 192.168.20.1](<../imagenes/DNS i DHCP/08-rutas-cliente-linux.png>)

*Figura 8. La ruta por defecto va por `192.168.20.1`, la IP de origen es `192.168.20.112` y aparece `proto dhcp`.*

La IP `192.168.20.112` está dentro del rango configurado y `proto dhcp` indica que la configuración la dio el DHCP. En el documento original se habla de una comprobación con `ip a`, pero la imagen que había junto a ese texto era otra vez la de Netplan, así que usé la captura de `ip route`.

También comprobé el nombre del router desde el cliente:

```bash
nslookup R-N02 192.168.20.10
```

![Consulta desde Linux que resuelve R-N02 como 192.168.20.1](<../imagenes/DNS i DHCP/09-prueba-dns-cliente-linux.png>)

*Figura 9. El DNS local responde a la consulta de `R-N02` con `192.168.20.1`.*

Esta consulta va dirigida expresamente a `192.168.20.10`, así que por sí sola no demuestra qué DNS usa Linux cuando no se le indica ninguno.

### 8.2. Cliente Windows: PCW-N02

En el cliente Windows miré la configuración de red con:

```bat
ipconfig /all
```

![Configuración de Windows con dirección 192.168.20.148 y servidor DNS y DHCP 192.168.20.10](<../imagenes/DNS i DHCP/10-ipconfig-cliente-windows.png>)

*Figura 10. Configuración que ha recibido el cliente Windows.*

En la captura se ve esto:

| Parámetro | Resultado |
| --- | --- |
| Nombre del equipo | `PCW-N02` |
| DHCP habilitado | Sí |
| Dirección IPv4 | `192.168.20.148` |
| Máscara | `255.255.255.0` |
| Puerta de enlace | `192.168.20.1` |
| Servidor DHCP | `192.168.20.10` |
| Servidor DNS | `192.168.20.10` |
| Concesión | Salen las fechas de obtención y caducidad, con 12 horas de diferencia. |

La IP está dentro del rango previsto y el DNS y la puerta de enlace coinciden con lo que puse en dnsmasq. Esta captura solo comprueba los datos que recibe el cliente; no hice ninguna consulta DNS desde Windows.

## 9. Resultados

| Comprobación | Evidencia | Resultado |
| --- | --- | --- |
| Instalación de dnsmasq | Figura 3 | Versión 2.91. |
| Servicio en ejecución e inicio automático | Figura 5 | `active (running)` y `enabled`. |
| Resolución del nombre interno `R` | Figura 6 | Devuelve `192.168.20.1`. |
| Resolución de un dominio externo | Figura 6 | `google.com` devuelve direcciones IP. |
| Parámetros DHCP en Linux | Figura 8 | IP `192.168.20.112` y puerta de enlace `192.168.20.1`. |
| Consulta DNS desde Linux | Figura 9 | `R-N02` devuelve `192.168.20.1`. |
| Parámetros DHCP y DNS en Windows | Figura 10 | IP `192.168.20.148`, DNS y DHCP `192.168.20.10`. |

Con estas pruebas se ve que el DNS y el DHCP funcionan en la Intranet, tanto con el cliente Linux como con el Windows.

## 10. Referencias

- Documento de la práctica **Explicación i Capturas-2.pdf**: páginas 3 a 6 para el servidor y páginas 8 a 10 para las pruebas de los clientes. Las imágenes están sacadas del PDF sin modificar.
- [Arquitectura e inventario del proyecto](../README.md).
- [Manual oficial de dnsmasq](https://thekelleys.org.uk/dnsmasq/docs/dnsmasq-man.html): qué hace cada directiva y cómo comprobar la sintaxis.
- [Documentación de Netplan](https://netplan.readthedocs.io/en/stable/examples/) y [órdenes de Netplan](https://netplan.io/): IPs estáticas, DHCP y cómo aplicar los cambios.
- [RFC 2131, sección 3.1](https://www.rfc-editor.org/rfc/rfc2131.html#section-3.1): mensajes y concesión de direcciones en DHCP.
