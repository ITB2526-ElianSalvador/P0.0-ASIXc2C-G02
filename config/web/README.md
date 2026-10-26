# Configuración y evidencias de W-N02

[Procedimiento y pruebas](<../../docs/Servidor web, SSH y aplicación de prueba.md>) · [Inventario general](../README.md)

| Archivo | Ubicación de referencia | Procedencia y alcance |
| --- | --- | --- |
| [netplan-web.yaml](netplan-web.yaml) | `/etc/netplan/00-installer-config.yaml` | Transcripción de las directivas activas visibles. IP `192.168.10.10/24`, gateway `192.168.10.1` y DNS `1.1.1.1`. |
| [evidencias/index.php.fragmento.txt](evidencias/index.php.fragmento.txt) | Inicio de `/var/www/html/index.php` | Transcripción de las primeras líneas legibles, con espaciado normalizado. No es el código completo ni un archivo listo para desplegar. |

La captura completa del fragmento PHP está en [imagenes/W-N02](../../imagenes/W-N02/07-fragmento-aplicacion-php.png). Permite leer más lógica de sesión y conexión, pero corta la continuación del programa. No se reconstruye la aplicación que no aparece.

Faltan el `index.php` real, la configuración efectiva de Apache y, para cerrar SSH en W-N02, sus ajustes y pruebas. No se crea un VirtualHost ni un `sshd_config` supuesto a partir de los valores predeterminados de otros equipos.

Antes de integrar el YAML debe revisarse la configuración efectiva de la VM y comprobar que la interfaz se llama `enp1s0`. Ningún archivo de esta entrega se ha aplicado al servidor.
