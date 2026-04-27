# DDEV - ATSA Tucuman

Este proyecto ya quedó preparado para correr con DDEV.

## Estado actual

- Docker Desktop instalado
- DDEV instalado
- Backup de base generado en:
  - `database/backups/atsa_tucuman_pre_ddev.sql`
- Configuración base creada en:
  - `.ddev/config.yaml`

## Bloqueo actual

En esta máquina, WSL2 / virtualización todavía no está operativo.

El error actual indica que falta habilitar:

- `Virtual Machine Platform`
- virtualización en BIOS/UEFI

Hasta que eso no esté resuelto, Docker Desktop no puede levantar bien el engine Linux y DDEV no puede arrancar.

## Cuando Windows ya esté listo

Ejecutar desde la raíz del proyecto:

```powershell
ddev start
```

Luego ajustar `.env` para DDEV con estos valores:

```env
APP_URL=https://atsa-tucuman.ddev.site

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=db
DB_USERNAME=db
DB_PASSWORD=db
```

Después:

```powershell
ddev import-db --src=database/backups/atsa_tucuman_pre_ddev.sql
ddev artisan key:generate
ddev artisan storage:link
ddev artisan migrate
ddev npm install
ddev npm run build
```

## URLs esperadas

- Sitio principal:
  - `https://atsa-tucuman.ddev.site`
- CENT:
  - `https://atsa-tucuman.ddev.site/cent74`
- Admin Filament:
  - `https://atsa-tucuman.ddev.site/admin`
- Admin CENT:
  - `https://atsa-tucuman.ddev.site/cent-admin/login`
