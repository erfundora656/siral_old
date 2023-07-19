# SiRAl virtualizado en contenedores de Docker

## Contenido
A continuación los pasos necesarios para llevar a cabo la instalación del sistema de forma rápida

### Requerimientos:
* Docker [`Guía de instalación de Docker Desktop`](https://docs.docker.com/desktop/)
* Docker Compose[`Guía de instalación de Docker Compose`](https://docs.docker.com/compose/install/)

La mayoría de los paquetes de Docker Desktop vienen con Docker Compose, excepto los paquetes de Linux.
En Linux `docker-compose` debe ser instalado de manera independiente.

### Intalación y configuración

1. Clonar el repositorio

```console
git clone -b develop git@git.uclv.edu.cu:damdiaz/siral.git
```
Deberán utilizar las credenciales de la UCLV para obtener el repositorio.

2. Configuración Inicial

Acceder a la carpeta del proyecto 
```console
cd siral
```

Copiar archivo de variables de entorno
```console
cp siraldock/.env.example ./.env
```
En caso de haber error en windows debe abrir el archivo `siraldock/.env.example` y guradar como... el nombre `.env`.

Ejecutar comando para levantar los contenedores de Docker.

```console
docker-compose up -d
```

Es probable que se necesite internet con VPN, de lo contrario puede utilizar [Nexus UCLV](https://nexus.uclv.edu.cu)

3. Migrar la base de datos

```console
/# docker-compose exec mysql bash
/# mysql -uroot -proot < /docker-entrypoint-initdb.d/siral.sql
```

Los comandos anteriores añaden la configuración del archivo `siraldock/mysql/docker-entrypoint-initdb.d/siral.sql` a la base de datos.

En caso de realizar alguna modificación a la base de datos se debe modificar ese archivo, eliminar la base de datos utilizando PhpMyAdmin y correr los siguientes comandos.

```console
/# docker-compose exec mysql bash
/# mysql -uroot -proot < /docker-entrypoint-initdb.d/siral.sql
```

4. Acceder a los servicios
* SiRAl: [http://localhost:8010](http://localhost:8010)
* PhpMyAdmin: [http://localhost:8003](http://localhost:8003)

### Conclusiones
Credenciales para PhpMyAdmin:

Servidor: `mysql`\
Usuario: `root`\
Contraseña: `root`

Credenciales para administración de SiRAl:

Usuario: `admin`\
Contraseña: `admin`

Para que los servicios estén corriendo, la condición necesaria es que Docker esté corriendo. 
La condición suficiente es la correcta configuración como se ha expuesto ateriormente.


###### Redacción y desarrollo de siraldock: Daniel Mesa