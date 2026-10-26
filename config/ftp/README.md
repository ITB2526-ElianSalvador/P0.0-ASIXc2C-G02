# Configuración de F-N02

[Procedimiento y pruebas](<../../docs/Servidor FTP.md>) · [Inventario general](../README.md)

| Archivo | Destino de referencia | Procedencia y alcance |
| --- | --- | --- |
| [netplan-ftp.yaml](netplan-ftp.yaml) | `/etc/netplan/00-installer-config.yaml` | Transcripción de las directivas activas visibles en el Word. IP `192.168.10.11/24`, gateway `192.168.10.1` y DNS `1.1.1.1`. |
| [vsftpd-fragmento.conf](vsftpd-fragmento.conf) | Parte de `/etc/vsftpd.conf` | Únicamente las tres opciones visibles de acceso anónimo, usuarios locales y escritura. No es el archivo completo. |

El YAML debe contrastarse con los demás archivos Netplan de la VM y con el nombre real de su interfaz antes de integrarlo. El fragmento de vsftpd no debe sustituir el archivo completo, porque no contiene opciones de escucha ni el resto de ajustes existentes.

Las capturas acreditan instalación y login, pero no una exportación completa de la configuración actual ni una prueba de transferencia de datos. Falta incorporar esa copia efectiva y los resultados de subida y descarga.
