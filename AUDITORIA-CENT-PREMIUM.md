# Auditoria CENT N74 - avance premium

Fecha: 2026-04-23

## Estado general

- Migraciones ejecutadas correctamente.
- Tests del modulo CENT: 13 tests OK, 95 assertions.
- Vistas Blade cacheadas correctamente.
- No se detectaron simbolos raros en los archivos PHP/Blade escaneados despues de esta tanda.
- El panel CENT suma recursos nuevos para aula virtual, cuotas, permisos de examen, legajos, equivalencias y auditoria.

## Nuevas funciones implementadas

- Aula virtual para alumnos: clases, materiales, trabajos practicos y entregas.
- Aula virtual para docentes: publicacion de clases, materiales y trabajos practicos desde el portal.
- Recursos admin CENT para clases, materiales, trabajos practicos, entregas y permisos de examen.
- Permisos de examen con estado, pago asociado, QR y descarga PDF.
- Carnet de estudiante con QR y descarga PDF.
- Verificacion publica de carnet de estudiante y permiso de examen.
- Preinscripcion filtrada: al elegir carrera se muestran solo las sedes que dictan esa carrera.
- Control backend para impedir preinscripcion en sede no habilitada para la carrera.
- Cuotas con alertas de deuda, vencidas y pagos confirmados en portal alumno.
- Datos demo para afiliados ATSA, alumnos CENT, docentes, directivos, cuotas, clases, materiales, TP, entregas y permisos.
- Separacion de usuarios: el admin CENT lista solo usuarios con `cent_role`; afiliados ATSA no se mezclan en usuarios CENT.
- Reportes exportables para direccion: alumnos, cuotas, preinscripciones, actas, mesas e inscripciones a finales en CSV, mas reporte general PDF.
- Permisos finos por sede en recursos principales del admin CENT: usuarios, matriculas, preinscripciones, comisiones, mesas, cuotas, legajos, aula virtual, permisos, recibos y notificaciones.
- Notificaciones internas persistentes para legajo, cuotas, aula virtual, entregas y permisos, con campanita en el portal.
- Recibos oficiales de cuotas con PDF y verificacion publica por QR.
- Configuracion propia del CENT en admin, con datos de contacto, redes, textos, pagos/descuentos y parametros SMTP editables.
- Limpieza de textos con codificacion rara en archivos PHP/Blade escaneados.
- Pantallas publicas nuevas para horarios y descargas del CENT.
- Recursos admin disponibles para gestionar horarios y descargas del CENT.
- Menu publico del CENT actualizado con Horarios y Descargas.
- Preinscripcion optimizada para cargar sedes por carrera sin consultas extra.
- Datos iniciales administrables para horarios de cursado y descargas institucionales.

## Datos demo creados

- Usuarios CENT: 16
- Alumnos CENT: 7
- Docentes CENT: 6
- Afiliados ATSA separados: 6
- Clases: 20
- Materiales: 20
- Trabajos practicos: 20
- Cuotas: 5
- Permisos de examen: 3

## Accesos utiles

- Sitio CENT: `/cent74`
- Login CENT: `/cent74/login`
- Admin CENT: `/cent-admin`
- Alumno aula virtual: `/cent74/alumno/aula`
- Alumno cuotas: `/cent74/alumno/cuotas`
- Alumno permisos: `/cent74/alumno/permisos`
- Alumno carnet: `/cent74/alumno/carnet`
- Docente aula virtual: `/cent74/docente/aula`
- Admin clases: `/cent-admin/clases`
- Admin materiales: `/cent-admin/materiales`
- Admin trabajos practicos: `/cent-admin/trabajos-practicos`
- Admin entregas: `/cent-admin/entregas`
- Admin permisos: `/cent-admin/permisos-examen`
- Admin recibos: `/cent-admin/recibos`
- Admin notificaciones: `/cent-admin/notificaciones`
- Admin configuracion CENT: `/cent-admin/configuracion-cent`
- Portal notificaciones: `/cent74/notificaciones`
- Horarios publicos: `/cent74/horarios`
- Descargas publicas: `/cent74/descargas`

## Verificacion final 2026-04-23

- `php artisan migrate`: OK, tablas `cent_horarios` y `cent_descargas` ejecutadas.
- `php artisan view:cache`: OK.
- `php artisan test --filter=Cent`: OK, 13 tests y 95 assertions.
- `npm run build`: OK.
- Rutas 200 verificadas:
  - `/cent74`
  - `/cent74/carreras`
  - `/cent74/sedes`
  - `/cent74/horarios`
  - `/cent74/descargas`
  - `/cent74/preinscripcion`
  - `/cent74/login`
  - `/cent-admin/login`

## Logins demo

Todas estas cuentas usan clave:

`Cent1234!`

- `centadmin@atsa.com`
- `docente.cent@atsa.com`
- `alumno.cent@atsa.com`
- `alumno.demo1@cent.com`
- `alumno.demo2@cent.com`
- `alumno.demo3@cent.com`
- `alumno.demo4@cent.com`
- `alumno.demo5@cent.com`
- `docente.cent1@atsa.com`
- `docente.cent2@atsa.com`
- `directivo.cent1@atsa.com`
- `directivo.cent2@atsa.com`

## Pendientes recomendados para siguiente tanda

- Pulir visualmente todas las pantallas nuevas del aula virtual con una revision fina en navegador.
- Agregar exportacion XLSX real ademas de CSV si la administracion lo pide.
- Conectar SMTP real cuando la comision apruebe host, usuario, clave y remitente.
- Crear comando programado para notificar automaticamente cuotas por vencer y cuotas vencidas.
- Completar carga real de correlatividades por carrera en admin.
- Revisar textos finales institucionales del CENT con la comision.
