# Router R-N02

Router que une las tres redes del enunciado: NAT (internet), DMZ e Intranet. Sistema: Ubuntu Server 24.04. Usuario `bchecker` / `bchecker121`.

## Tarjetas de red

| Tarjeta | Red de Isard | Función | IP |
| --- | --- | --- | --- |
| enp1s0 | Default | NAT (internet) | por DHCP |
| enp2s0 | ITB 2 | DMZ | 192.168.10.1/24 |
| enp3s0 | ITB 1 | Intranet | 192.168.20.1/24 |

## Configuración de red

Fichero `/etc/netplan/50-cloud-init.yaml`, aplicado con `sudo netplan apply`:

```yaml
network:
  version: 2
  ethernets:
    enp1s0:
      dhcp4: true
    enp2s0:
      addresses: [192.168.10.1/24]
    enp3s0:
      addresses: [192.168.20.1/24]
```

## Reenvío de paquetes

En `/etc/sysctl.conf` se activa la línea `net.ipv4.ip_forward=1` y `sudo sysctl -p` la aplica. Con esto el router pasa tráfico de una red a otra.

## Salida a internet (NAT)

Los equipos de la DMZ y de la Intranet tienen IPs privadas que internet no conoce. Con este comando, el router cambia la IP de origen de sus paquetes por la suya cuando salen por `enp1s0`:

```
sudo iptables -t nat -A POSTROUTING -o enp1s0 -j MASQUERADE
```

La regla se guarda con `iptables-persistent` (`sudo netfilter-persistent save`) para que no se pierda al reiniciar.
