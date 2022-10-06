
# Sistema de seguimiento de Alumnos

TODO: Descripción del sistema

## Instalación para desarrollo

 Instala [Laragon](https://laragon.org/).

 Suponiendo que se utilizó el directorio por defecto durante la instalación, crear un archivo llamado `sistema-seguimiento-alumnos.conf` en el directorio `C:\laragon\etc\apache2\sites-enabled\` con el siguiente contenido:

```xml
<VirtualHost *:80> 
    DocumentRoot "C:\PATH\TO\PROJECT\public"
    ServerName sistema-seguimiento.test
    ServerAlias *.sistema-seguimiento.test
    <Directory "C:\PATH\TO\PROJECT\public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

`C:\PATH\TO\PROJECT` debe sustituirse por el directorio del proyecto.

Después de reiniciar Laragon, se deben ejecutar los siguientes comandos:

```powershell
composer install
copy .env.example .env 
php artisan key:generate
```
En la base de datos de laragon (HeidiSQL), crear una base de datos llamada 'saa', correr migraciones  y servidor.

```powershell
php artisan migrate:fresh
php artisan serve
```
En la terminal de laragon ingresar el comando:
```powershell 
composer require laravel/ui
```
En el archivo vendor/laravel/ui/auth-backend/AuthenticatesUsers.php linea 157 modificar 'email' por 'username'

# Iniciar Servidor Python

Si es la primera vez que se ejecuta el servidor, se debe crear un entorno virtual de python y activarlo. Para esto, se debe ejecutar el siguiente comando en la carpeta del proyecto:

```powershell
python -m venv .venv
```
## Activar entorno virtual

**Windows Powershell:**

```powershell
& .venv\Scripts\Activate.ps1
```

**Windows CMD:**

```cmd
.venv\Scripts\activate.bat
```

**Linux/MacOS:**

```bash
source .venv/bin/activate
```

## Instalar dependencias

```shell
pip install -r graph/requirements.txt
```

## Ejecutar servidor

```shell
uvicorn graph.graph_api:graphAPI
```