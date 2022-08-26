
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
```composer require laravel/ui
```