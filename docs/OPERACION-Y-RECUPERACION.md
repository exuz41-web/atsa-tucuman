# Operación y Recuperación

Guía práctica para levantar, mantener y recuperar el sistema ATSA Tucumán / CENT N°74.

## Datos del repositorio

Repositorio GitHub:

```text
https://github.com/exuz27/atsa-tucuman
```

Rama principal:

```text
master
```

Carpeta local Laragon:

```text
C:\laragon\www\atsa-tucuman
```

## Recuperar el proyecto desde cero

1. Clonar el repositorio:

```powershell
cd C:\laragon\www
git clone https://github.com/exuz27/atsa-tucuman.git
cd C:\laragon\www\atsa-tucuman
```

2. Instalar dependencias:

```powershell
composer install
npm install
```

3. Crear `.env`:

```powershell
copy .env.example .env
php artisan key:generate
```

4. Configurar `.env` con los datos reales:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=atsa_tucuman
DB_USERNAME=usuario
DB_PASSWORD=clave

BACKUP_ENABLED=true
BACKUP_SCHEDULE=02:30
BACKUP_KEEP_DAYS=14
```

5. Migrar base y compilar assets:

```powershell
php artisan migrate --force
npm run build
php artisan storage:link
php artisan optimize:clear
php artisan optimize
```

## Actualizar desde GitHub

```powershell
cd C:\laragon\www\atsa-tucuman
git pull origin master
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
```

Antes de actualizar producción, generar backup:

```powershell
php artisan backups:run
```

## Backups

Crear backup manual:

```powershell
php artisan backups:run
```

Crear backup y conservar solo los últimos 30 días:

```powershell
php artisan backups:run --keep-days=30
```

Ubicación:

```text
storage/app/private/backups
```

Cada backup incluye:

- `metadata.json`
- `database.sql`
- `storage/public`
- `storage/private`, excepto la carpeta interna de backups

## Scheduler

El scheduler corre lo definido en `routes/console.php`, incluyendo:

- `carnets:notificar`
- `backups:run`

En Linux/hosting con cron:

```bash
* * * * * cd /ruta/al/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

En Windows/Laragon, crear una tarea programada que ejecute cada minuto:

```powershell
cd C:\laragon\www\atsa-tucuman; php artisan schedule:run
```

Si `php` no está en el PATH:

```powershell
cd C:\laragon\www\atsa-tucuman; & 'C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe' artisan schedule:run
```

## Restaurar un backup

1. Descomprimir el `.tar.gz` o `.tar` del backup.

2. Restaurar base de datos MySQL:

```powershell
mysql -u usuario -p atsa_tucuman < database.sql
```

3. Restaurar storage:

```powershell
Copy-Item -Recurse -Force .\storage\public\* C:\laragon\www\atsa-tucuman\storage\app\public\
Copy-Item -Recurse -Force .\storage\private\* C:\laragon\www\atsa-tucuman\storage\app\private\
```

4. Rehacer enlace público de storage y limpiar caches:

```powershell
php artisan storage:link
php artisan optimize:clear
php artisan optimize
```

5. Probar login y flujos críticos.

## Checklist de producción

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL` con dominio real y HTTPS.
- Base de datos con usuario dedicado.
- `.env` fuera de Git.
- `storage` y `bootstrap/cache` con permisos de escritura.
- `php artisan storage:link` ejecutado.
- `npm run build` ejecutado.
- `php artisan migrate --force` ejecutado.
- Scheduler activo.
- Backups activos y descargados periódicamente fuera del servidor.
- `php artisan test` en verde antes de desplegar cambios importantes.

## Flujos críticos para probar

- Sitio público ATSA.
- Solicitud de afiliación y descarga de PDF por token.
- Login de afiliados.
- Pedidos, consultas y solicitudes de beneficios.
- Panel admin ATSA.
- Gestión de backups desde panel.
- Sitio público CENT.
- Preinscripción CENT y ficha por token.
- Login alumno/docente/directivo.
- Aula, legajo, cuotas, trabajos, actas y reportes CENT.

## Comandos útiles

Estado Git:

```powershell
git status
git log --oneline -5
```

Subir cambios:

```powershell
git add .
git commit -m "Mensaje claro del cambio"
git push origin master
```

Limpiar caches:

```powershell
php artisan optimize:clear
```

Ver rutas:

```powershell
php artisan route:list
```

Ejecutar pruebas:

```powershell
php artisan test
```

## Notas de seguridad

- Los documentos sensibles se guardan en `storage/app/private`.
- Los enlaces públicos sensibles usan tokens, no IDs ni DNI.
- Los permisos del panel se centralizan en `config/permissions.php`.
- No publicar `.env`, backups ni `storage/app/private`.
- Descargar copias de backups fuera del servidor de forma periódica.
