
# Sistema de seguimiento de Alumnos

TODO: Descripción del sistema

## Instalación para desarrollo

 Instala [Laragon](https://laragon.org/).

 Suponiando que se utilizo el directorio por defecto durante la instalación, crear un archivo llamado `sistema-seguimiento-alumnos.conf` en el directorio `C:\laragon\etc\apache2\sites-enabled\` con el siguiente contenido:

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

Despues de reiniciar Laragon, se deben ejetuar los siguientes comandos:

```powershell
composer install
copy .env.example .env 
php artisan key:generate
```