# Deploy rapido en Railway

Este proyecto ya quedo preparado para subirlo a Railway con `Dockerfile`.

## 1. Login

En tu terminal local:

```powershell
railway login
```

Si queres modo browserless:

```powershell
railway login --browserless
```

## 2. Crear proyecto

Desde la raiz del proyecto:

```powershell
railway init -n atsa-tucuman-demo
```

## 3. Primer deploy

```powershell
railway up
```

Con eso Railway ya puede levantar el contenedor.

## 4. Variables recomendadas

Configura estas variables en Railway:

```text
APP_NAME=ATSA Tucuman
APP_ENV=production
APP_DEBUG=false
APP_URL=https://TU-DOMINIO.railway.app
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_AR
APP_TIMEZONE=America/Argentina/Tucuman
FILESYSTEM_DISK=public
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=database
MAIL_MAILER=log
RUN_DEMO_SEEDERS=true
```

## 5. Base de datos

### Opcion rapida para demo

No pongas variables de base de datos y el contenedor levantara con SQLite.

Sirve para staging/demo rapido.

### Opcion recomendada

Agrega un servicio MySQL en Railway y luego define:

```text
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

Despues redeploy:

```powershell
railway up
```

## 6. Datos demo

Si queres que suba con contenido demo del CENT:

```text
RUN_DEMO_SEEDERS=true
```

Una vez cargado, podes dejarlo en `false` para siguientes despliegues.

## 7. URL publica

Para abrir la URL del servicio:

```powershell
railway open
```

## 8. Notas importantes

- `storage:link` se ejecuta al arrancar.
- `php artisan migrate --force` se ejecuta al arrancar.
- Este deploy esta pensado como entorno demo/staging.
- Para produccion conviene sumar almacenamiento persistente y SMTP real.
