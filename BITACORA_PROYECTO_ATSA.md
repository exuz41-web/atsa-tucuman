# Bitacora del Proyecto ATSA Tucuman

Actualizado: 2026-04-25  
Proyecto local: `C:\laragon\www\atsa-tucuman`  
Stack principal: Laravel, Filament, Blade, Vite, Tailwind, Modernize, Tabler Icons, DomPDF, QR.

Esta bitacora existe para retomar el proyecto aunque se apague la maquina, cambie el contexto o se pierda memoria de la conversacion. La idea es que cualquier sesion futura entienda rapidamente que es el sistema, que ya tiene hecho, que falta y como seguir sin romper lo avanzado.

## 1. Vision General

El proyecto empezo como sitio publico de ATSA Tucuman, pero evoluciono hacia una plataforma institucional completa.

Hoy conviven tres grandes frentes:

1. Sitio publico ATSA Tucuman.
2. Portal privado de afiliados.
3. Panel administrativo ATSA.
4. Sitio y sistema academico separado para CENT Nro 74.

Decision importante: el CENT no debe mezclarse con el sistema sindical de ATSA. Puede vivir bajo el mismo proyecto y dominio/subdominio, pero conceptualmente es un sistema separado: usuarios, roles, alumnos, docentes, directivos y administracion academica no deben confundirse con afiliados sindicales.

## 2. Estado Actual del Proyecto

El sistema ya tiene una base grande y funcional:

- Web publica con home, sindicato, gremial, afiliados, filiales, novedades, turismo, beneficios, contacto y paginas institucionales.
- Admin ATSA con Filament.
- Portal de afiliados con login, registro, dashboard, carnet digital, pedidos, consultas, beneficios, descargas y testimonios.
- Carnet digital de afiliado con QR, verificacion publica y descarga.
- Novedades con posts reales, imagenes, comentarios moderados y marca de agua automatica.
- Gestion de beneficios, turismo, hoteles/convenios, documentos, efemerides y testimonios.
- CENT Nro 74 con sitio publico, carreras, sedes, preinscripcion, horarios, descargas y contacto.
- Portal CENT para alumnos, docentes y directivos.
- Admin CENT separado en `/cent-admin`.
- Deploy demo en Railway preparado y probado.
- Configuracion DDEV iniciada en `.ddev`.

## 3. Rutas Principales

### Web publica ATSA

- `/`
- `/el-sindicato`
- `/gremial`
- `/afiliados`
- `/afiliacion`
- `/filiales`
- `/novedades`
- `/novedades/{slug}`
- `/contacto`
- `/turismo`
- `/escalas-salariales`
- `/delegados`
- `/documentos`
- `/efemerides`
- `/sitemap.xml`

### Portal afiliados

- `/afiliados/login`
- `/afiliados/registro`
- `/afiliados/recuperar-password`
- `/afiliados/dashboard`
- `/afiliados/mi-carnet`
- `/afiliados/mis-datos`
- `/afiliados/mis-pedidos`
- `/afiliados/nuevo-pedido`
- `/afiliados/mis-consultas`
- `/afiliados/beneficios`
- `/afiliados/descargas`
- `/afiliados/mi-testimonio`

### Carnet afiliado

- `/verificar/{numero_afiliado}` publica
- `/afiliados/mi-carnet`
- `/afiliados/mi-carnet/descargar`
- `/afiliados/mi-carnet/imagen`
- `/afiliados/mi-carnet/wallet-data`

### CENT Nro 74 publico

- `/cent74`
- `/cent74/carreras`
- `/cent74/carreras/{slug}`
- `/cent74/sedes`
- `/cent74/requisitos`
- `/cent74/preguntas-frecuentes`
- `/cent74/novedades`
- `/cent74/mesas-de-examen`
- `/cent74/contacto`
- `/cent74/preinscripcion`
- `/cent74/preinscripcion/consulta`
- `/cent74/horarios`
- `/cent74/descargas`

### Portal CENT

- `/cent74/login`
- `/cent74/portal`
- `/cent74/alumno/dashboard`
- `/cent74/alumno/aula`
- `/cent74/alumno/cuotas`
- `/cent74/alumno/permisos`
- `/cent74/alumno/carnet`
- `/cent74/docente/dashboard`
- `/cent74/docente/aula`
- `/cent74/docente/comisiones`
- `/cent74/docente/mesas`
- `/cent74/directivo/dashboard`
- `/cent74/directivo/alumnos`
- `/cent74/directivo/docentes`
- `/cent74/directivo/comisiones`
- `/cent74/directivo/reportes`

### Admin

- ATSA: `/admin`
- CENT: `/cent-admin`

## 4. Credenciales Demo Conocidas

Estas credenciales fueron usadas durante el desarrollo y pueden cambiar si se vuelven a sembrar datos.

### Admin ATSA

- Email: `admin@atsa.com`
- Password: `Admin1234!`

### CENT

- Admin/directivo demo: `centadmin@atsa.com`
- Docente demo: `docente.cent@atsa.com`
- Alumno demo: `alumno.cent@atsa.com`
- Alumna demo: `alumna.cent@atsa.com`
- Password habitual CENT: `Cent1234!`

Si no entran, revisar seeders y usuarios en base de datos.

## 5. Modulos ATSA

### Gestion web

Modulo orientado a administrar lo visible en el sitio publico.

Incluye:

- Novedades/posts.
- Comentarios de novedades.
- Paginas institucionales.
- Bloques visuales.
- Secciones editables.
- Efemerides.
- Testimonios.
- Descargas publicas.
- Beneficios.
- Turismo.
- Hoteles/convenios.
- Configuracion visual.

Recursos relevantes:

- `PostResource`
- `PostCommentResource`
- `SitePageResource`
- `PageSectionResource`
- `VisualBlockResource`
- `EfemerideResource`
- `TestimonioResource`
- `DescargaResource`
- `BeneficioResource`
- `HotelConvenioResource`
- `SiteSettingResource`

### Padron sindical

Modulo en crecimiento. La idea es que el padron no sea solamente un login, sino una base sindical real.

Campos agregados o contemplados:

- Tipo de afiliado: estatal / privado.
- Delegado gremial.
- Congresal.
- Establecimiento laboral.
- Secretaria vinculada.
- Perfil interno.
- Legajo laboral.
- Acceso a todas las filiales.

Recurso principal:

- `UserResource`

### Institucion

Modulo para representar la estructura interna y territorial.

Incluye:

- Filiales gremiales.
- Autoridades.
- Delegados.
- Secretarias.
- Establecimientos.
- Documentos institucionales.

Recursos relevantes:

- `FilialResource`
- `AutoridadResource`
- `DelegadoResource`
- `SecretariaResource`
- `EstablecimientoResource`
- `DocumentoResource`

### Atencion al afiliado

Incluye:

- Pedidos.
- Consultas.
- Solicitudes de afiliacion.
- Solicitudes de beneficios.
- Carnets.
- Turismo consultas.
- Beneficios.

Recursos relevantes:

- `PedidoResource`
- `ConsultaResource`
- `SolicitudAfiliacionResource`
- `SolicitudBeneficioResource`
- `TurismoConsultaResource`
- `UserResource`
- `GestionCarnets`

### Gremial

Incluye:

- Escalas salariales.
- Delegados.
- Documentos gremiales.
- Contenido institucional gremial.

Recurso relevante:

- `EscalaSalarialResource`

## 6. Modulos CENT Nro 74

El CENT tiene administracion propia y debe mantenerse separado conceptualmente de ATSA sindical.

Incluye:

- Carreras.
- Materias.
- Sedes.
- Preinscripciones.
- Matriculas.
- Comisiones.
- Inscripciones.
- Notas.
- Mesas de examen.
- Aulas virtuales.
- Clases.
- Materiales.
- Trabajos practicos.
- Entregas.
- Cuotas.
- Recibos.
- Permisos de examen con QR.
- Carnet estudiantil con QR.
- Avisos.
- Eventos.
- Legajo documental.
- Reportes.

Recursos Filament relevantes:

- `CarreraResource`
- `MateriaResource`
- `CentSedeResource`
- `PreinscripcionCentResource`
- `MatriculaCentResource`
- `ComisionResource`
- `InscripcionResource`
- `NotaResource`
- `MesaExamenCentResource`
- `CentClaseResource`
- `CentMaterialResource`
- `CentTrabajoPracticoResource`
- `CentEntregaTrabajoResource`
- `CentCuotaResource`
- `CentReciboResource`
- `CentPermisoExamenResource`
- `CentUserResource`
- `CentConfiguracionResource`

## 7. Filiales y Datos Institucionales Confirmados

### Filiales gremiales ATSA

ATSA Tucuman tiene tres filiales gremiales principales mencionadas por el usuario:

1. Central / Ciudad Deportiva: Paraguay y Thames, San Miguel de Tucuman.
2. Filial Este: Camino del Carmen 90, Banda del Rio Sali.
3. Filial del Sur: Julio Argentino Roca 371, Concepcion.

La sede central ya no es Suipacha 553 para la web principal; usar Paraguay y Thames como referencia actual.

### Filiales/sedes CENT mencionadas

Se mencionaron sedes y delegaciones para CENT en:

- Capital / San Miguel de Tucuman.
- Trancas.
- Delfin Gallo.
- Banda del Rio Sali.
- Concepcion.
- Los Ralos.
- Simoca.
- Santa Rosa de Leales.
- Tafi Viejo.
- Lules.
- Graneros.
- Aguilares.
- La Ramada.
- Amaicha del Valle.
- Famailla.
- Monteros.

## 8. Secretarias y Comision Directiva

Se cargo una primera base de secretarias desde una foto enviada por el usuario.

Secretarias/cargos detectados:

1. Secretario General.
2. Secretario General Adjunto.
3. Secretario de Finanzas.
4. Prosecretario de Finanzas.
5. Secretario Gremial.
6. Prosecretario Gremial.
7. Secretario de Prensa y Propaganda.
8. Secretario de Prevision y Accion Social.
9. Secretario del Interior.
10. Secretario de Actas.
11. Secretario de Turismo y Vivienda.
12. Secretario de Deportes y Juventud.
13. Prosecretario de Deportes y Juventud.
14. Secretaria de Igualdad de Oportunidades y Genero.
15. Secretaria de Capacitacion y Formacion Profesional.
16. Prosecretaria de Capacitacion y Formacion Profesional.
17. Secretaria de Organizacion y Relaciones Institucionales.
18. Secretaria de Higiene, Seguridad y Medicina del Trabajo.

Personas confirmadas por el usuario:

- Secretario General: Rene Ramirez / Edgar Renee Ramirez.
- Secretario Adjunto: Dario Ramirez.
- Secretaria de Finanzas: Mabel Aguirre.
- Secretaria Gremial: Alejandra Ferreyra.

Nota: conviene cargar cargos y areas por separado en una etapa futura para no mezclar "secretaria" como area con "cargo" de una persona.

## 9. Establecimientos

Se creo el modulo `Establecimientos` para cargar:

- Hospitales.
- CAPS.
- CIC.
- Sanatorios.
- Clinicas.
- Centros medicos.
- Otros.

Campos principales:

- Nombre.
- Tipo.
- Sector publico/privado.
- Filial asociada.
- Direccion.
- Localidad.
- Telefono.
- Email.
- Responsable.
- Activo.

Este modulo sera importante para:

- Padron sindical.
- Delegados.
- Congresales.
- Expedientes.
- Mesa de entradas.
- Estadisticas por lugar de trabajo.

## 10. Novedades y Contenido Real Cargado

Se cargaron varias notas reales con imagenes enviadas por el usuario.

Ejemplos:

- Convenio entre ATSA Tucuman y Municipalidad de Banda del Rio Sali.
- Alianza ATSA Tucuman - Libertad S.A.
- Alianza ATSA Tucuman - CEO.
- ATSA Tucuman junto a cada companero de la salud.

Las novedades:

- Se muestran automaticamente en ultimas noticias si estan publicadas.
- Pueden aparecer en destacadas si tienen el campo correspondiente.
- Tienen comentarios con moderacion.
- Las imagenes principales de posts reciben marca de agua automatica.

## 11. Comentarios en Novedades

Se agrego sistema de comentarios:

- Formulario publico debajo de cada novedad.
- Los comentarios nuevos quedan pendientes.
- Solo se muestran comentarios aprobados.
- Administracion desde Filament.

Archivos clave:

- `app/Models/PostComment.php`
- `app/Filament/Resources/PostCommentResource.php`
- `database/migrations/2026_04_24_120000_create_post_comments_table.php`
- `resources/views/novedades/show.blade.php`
- `app/Http/Controllers/NovedadesController.php`

Mejora pendiente:

- Respuestas oficiales de ATSA.
- Badge "Equipo ATSA".
- Contador en cards.
- Antispam/honeypot.

## 12. Marca de Agua

Se implemento marca de agua automatica para imagenes de novedades.

Archivo clave:

- `app/Support/ImageWatermarkSupport.php`

Modelo conectado:

- `app/Models/Post.php`

Comportamiento:

- Al subir o cambiar imagen principal de una novedad, se aplica logo de ATSA en esquina inferior derecha.
- No aplicar a fotos de perfil, documentos, DNI, carnets ni archivos sensibles.

Mejora pendiente:

- Checkbox en admin: "Aplicar marca de agua".
- Configurar opacidad, tamano y posicion desde Configuracion.

## 13. Afiliacion

Existe pagina publica:

- `/afiliacion`

Permite:

- Cargar solicitud.
- Adjuntar documentacion.
- Previsualizar formulario.
- Imprimir formulario.

Problema actual:

- El formulario impreso necesita pulido fuerte. La version anterior salia en varias hojas y luego fue compactada, pero el usuario la vio desalineada y fea.
- Objetivo: dejar una sola hoja A4, sobria, similar al formulario real enviado por foto, con membrete y marca de agua.

Pendiente prioritario:

- Redisenar completamente la vista imprimible como formulario A4 real, con medidas fijas y CSS de impresion limpio.

## 14. Admin ATSA

Admin basado en Filament:

- URL: `/admin`
- Provider: `app/Providers/Filament/AdminPanelProvider.php`
- Theme: `resources/css/filament/admin/theme.css`
- Sidebar custom publicado: `resources/views/vendor/filament-panels/components/sidebar/index.blade.php`

Se adapto visualmente hacia Modernize:

- Sidebar.
- Header/topbar.
- Perfil de usuario.
- Iconos Tabler.
- Sidebar colapsable.
- Estilo compacto.

Cambio reciente:

- Se compacto el sidebar para que los modulos ocupen menos espacio.
- Se agrego soporte visual basico para subitems.
- Se ejecuto `npm run build`.

Advertencia:

- Hay muchas vistas publicadas de Filament en `resources/views/vendor/filament-panels`. Esto da control visual, pero aumenta riesgo con actualizaciones de Filament.

## 15. Admin CENT

Admin separado:

- URL: `/cent-admin`
- Provider: `app/Providers/Filament/CentPanelProvider.php`

Debe mantenerse separado del admin sindical aunque use recursos y usuarios del mismo Laravel.

## 16. Deploy y Entornos

### Local

Proyecto local actual:

- `C:\laragon\www\atsa-tucuman`

El usuario tuvo problemas con Laragon y se hablo de pasar a DDEV.

Existe carpeta:

- `.ddev`

Pendiente:

- Verificar DDEV de punta a punta.
- Documentar comandos exactos cuando quede estable.

### Railway

Se preparo deploy demo en Railway.

Archivos:

- `Dockerfile`
- `docker/start.sh`
- `.railwayignore`
- `DEPLOY-RAILWAY.md`

URL demo conocida:

- `https://atsa-tucuman-demo-production.up.railway.app`

Problemas resueltos:

- Build fallaba por dependencias/extensiones.
- Assets se cargaban por `http` y el navegador los bloqueaba en `https`.
- Se forzo HTTPS en produccion desde `AppServiceProvider`.

## 17. Comandos Utiles

Usar PHP de Laragon si el comando `php` no apunta a la version correcta:

```powershell
& 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe' artisan migrate
```

Comandos comunes:

```powershell
php artisan migrate
php artisan optimize:clear
php artisan view:clear
php artisan view:cache
php artisan test
npm run build
```

Para Railway:

```powershell
railway login
railway up
railway deployment list --service atsa-tucuman-demo
railway logs --service atsa-tucuman-demo
```

## 18. Problemas Conocidos

### Textos rotos / mojibake

Todavia aparecen textos con caracteres rotos por codificacion. Ejemplos equivalentes:

- Gestion con tilde rota.
- Padron con tilde rota.
- Configuracion con tilde rota.
- CENT Nro 74 con simbolo de numero roto.
- Publico con tilde rota.

Se vieron en:

- `app/Providers/Filament/AdminPanelProvider.php`
- `app/Providers/Filament/CentPanelProvider.php`
- algunas vistas publicas antiguas

Pendiente:

- Hacer pasada controlada de normalizacion UTF-8 en todo `app` y `resources/views`.
- Revisar visualmente despues de limpiar.

### Formulario de afiliacion impreso

Pendiente de redisenar bien.

### Sidebar admin

Fue compactado, pero hay que validar visualmente en navegador.

### Contenido duro en Blade

Muchas paginas aun mezclan contenido en Blade con contenido administrable desde base. La direccion correcta es seguir migrando contenido institucional a admin sin perder control de layout.

### Filament overrides

Hay 53+ vistas publicadas de Filament. Conviene revisar cuales son necesarias y cuales se podrian reemplazar por theme/hooks para bajar deuda futura.

## 19. Proximos Pasos Recomendados

### Prioridad 1: orden y calidad inmediata

1. Limpiar textos rotos en admin, web publica y CENT.
2. Revisar sidebar admin compactado.
3. Redisenar formulario imprimible de afiliacion en una sola A4.
4. Validar novedades despues de comentarios y marca de agua.
5. Revisar mobile de home, novedades, afiliacion y portal afiliados.

### Prioridad 2: admin como sistema

1. Terminar de ordenar grupos del admin:
   - Gestion web.
   - Padron sindical.
   - Institucion.
   - Atencion al afiliado.
   - Gremial.
   - Configuracion.
2. Crear roles/permisos reales.
3. Definir perfiles internos:
   - Gerencia general.
   - Recepcion.
   - Secretaria.
   - Responsable filial.
   - Padron.
   - Consulta.
4. Implementar acceso por filial y acceso global.

### Prioridad 3: padron sindical real

1. Cargar establecimientos de Tucuman.
2. Relacionar afiliados con establecimiento, tipo y filial.
3. Gestionar delegados gremiales y congresales.
4. Reportes por filial, sector y establecimiento.

### Prioridad 4: nuevos modulos internos futuros

Los pidieron en conversacion, pero aun no se implementaron:

1. Mesa de entradas / expedientes.
2. Derivacion a secretarias.
3. Depositos por filial.
4. Inventario / stock.
5. Movimientos de stock.
6. Salones de fiesta.
7. Reservas y calendario.
8. Reportes gerenciales.

## 20. Arquitectura Futura Sugerida

No intentar construir todo junto. El proyecto debe crecer por modulos.

Orden recomendado:

1. Base institucional: filiales, secretarias, establecimientos, roles.
2. Padron sindical completo.
3. Mesa de entradas.
4. Expedientes y derivaciones.
5. Inventario por deposito/filial.
6. Salones y reservas.
7. Reportes, auditoria y permisos finos.

Regla general:

- Si es comunicacion publica, va en Gestion web.
- Si es afiliado o solicitud del afiliado, va en Atencion al afiliado.
- Si es estructura de ATSA, va en Institucion.
- Si es proceso interno, va a un modulo operativo nuevo.
- Si es CENT, queda separado en CENT.

## 21. Decisiones Tomadas

- Usar Blade como base del frontend, no GrapeJS para todo.
- GrapeJS podria servir en el futuro solo para landings o bloques visuales, pero no para paginas dinamicas, formularios, novedades ni portales.
- CENT debe ser independiente de ATSA sindical.
- La web publica debe seguir siendo editable progresivamente desde admin, sin perder control del layout.
- El sistema debe crecer modularmente segun lo que ATSA vaya pidiendo.
- El gerente general o admin central deberia tener acceso a todo; usuarios de filial o secretaria deben tener permisos restringidos.

## 22. Archivos Clave

### Providers

- `app/Providers/Filament/AdminPanelProvider.php`
- `app/Providers/Filament/CentPanelProvider.php`
- `app/Providers/AppServiceProvider.php`

### Layouts

- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/afiliado.blade.php`
- `resources/views/layouts/cent.blade.php`
- `resources/views/layouts/cent-public.blade.php`

### Admin theme

- `resources/css/filament/admin/theme.css`
- `resources/views/vendor/filament-panels/components/sidebar/index.blade.php`

### Rutas

- `routes/web.php`
- `routes/console.php`

### Novedades

- `app/Models/Post.php`
- `app/Models/PostComment.php`
- `app/Http/Controllers/NovedadesController.php`
- `resources/views/novedades/index.blade.php`
- `resources/views/novedades/show.blade.php`

### Afiliacion

- `app/Http/Controllers/SolicitudAfiliacionController.php`
- `resources/views/afiliacion/create.blade.php`

### CENT

- `app/Http/Controllers/CentController.php`
- `app/Http/Controllers/CentLoginController.php`
- `app/Http/Controllers/CentAlumnoController.php`
- `app/Http/Controllers/CentDocenteController.php`
- `app/Http/Controllers/CentDirectivoController.php`
- `resources/views/cent74`

### Nuevos modulos base ATSA

- `app/Models/Secretaria.php`
- `app/Models/Establecimiento.php`
- `app/Filament/Resources/SecretariaResource.php`
- `app/Filament/Resources/EstablecimientoResource.php`
- `database/seeders/SecretariasBaseSeeder.php`

## 23. Regla Para Retomar Trabajo

Cuando se retome el proyecto:

1. Leer esta bitacora primero.
2. Correr `php artisan test` si se tocaron modulos sensibles.
3. Correr `npm run build` si se tocaron CSS, JS, Vite o views de Filament.
4. No mezclar CENT con afiliados ATSA.
5. No borrar overrides ni archivos viejos sin revisar si se usan.
6. Antes de cambios grandes, revisar rutas y recursos existentes.
7. Mantener el admin organizado por modulos.
8. Registrar aqui las decisiones nuevas importantes.

## 24. Pendiente Inmediato Sugerido

Siguiente trabajo recomendado:

1. Probar visualmente sidebar admin compactado.
2. Redisenar formulario imprimible de afiliacion.
3. Crear base formal de roles/permisos.
4. Cargar establecimientos reales de Tucuman.
5. Revisar home y novedades en mobile.
6. Seguir puliendo pagina publica ATSA, empezando por textos rotos, novedades, afiliacion y home.

## 25. Ultima Sesion Registrada

Trabajo realizado en esta sesion:

1. Se creo esta bitacora raiz para continuidad del proyecto.
2. Se limpiaron textos rotos visibles en:
   - `app/Providers/Filament/AdminPanelProvider.php`
   - `app/Providers/Filament/CentPanelProvider.php`
   - `resources/views/layouts/cent.blade.php`
   - `app/Http/Controllers/SolicitudAfiliacionController.php`
3. Se limpiaron caches con:
   - `php artisan view:clear`
   - `php artisan optimize:clear`
4. Se verifico sintaxis PHP sin errores en providers y controlador.

Pendiente corto que quedo identificado:

- En `resources/views/layouts/app.blade.php` sobrevive una linea de `alt` del logo con codificacion vieja. No afecta fuerte al usuario visualmente, pero conviene limpiarla en la proxima pasada.



## 26. Actualizacion Sidebar Admin Modernize

Fecha: 2026-04-25.

Se dejo el sidebar del admin ATSA mas cercano al estilo Modernize y mas compacto para que los modulos no ocupen tanto espacio.

Cambios realizados:

1. El sidebar custom de Filament ahora renderiza grupos como modulos desplegables tipo acordeon.
2. Cada modulo tiene icono Tabler, texto y flecha de apertura/cierre.
3. Los recursos quedan dentro del modulo correspondiente, reduciendo mucho el alto visual del menu.
4. El modulo activo queda abierto automaticamente.
5. Se ajusto el modo mini-sidebar para mostrar iconos y ocultar listas internas.
6. Se normalizo el orden principal del admin:
   - Editor del sitio - ATSA.
   - Gestion web.
   - Padron sindical.
   - Institucion.
   - Gremial.
   - Atencion al afiliado.
   - Afiliados.
   - Configuracion.
7. Se corrigieron textos rotos visibles en recursos del admin relacionados a escalas salariales y hoteles convenio.

Archivos modificados:

- `resources/views/vendor/filament-panels/components/sidebar/index.blade.php`
- `resources/css/filament/admin/theme.css`
- `app/Providers/Filament/AdminPanelProvider.php`
- `app/Filament/Resources/EscalaSalarialResource.php`
- `app/Filament/Resources/HotelConvenioResource.php`
- `app/Filament/Pages/EditarContacto.php`
- `app/Filament/Pages/EditarGremial.php`
- `app/Filament/Pages/EditarInicio.php`
- `app/Filament/Pages/EditarSindicato.php`
- `app/Filament/Pages/EditarTurismo.php`

Verificacion realizada:

```powershell
php artisan view:cache
npm run build
php -l app/Providers/Filament/AdminPanelProvider.php
php -l app/Filament/Resources/EscalaSalarialResource.php
php -l app/Filament/Resources/HotelConvenioResource.php
php -l resources/views/vendor/filament-panels/components/sidebar/index.blade.php
```

Resultado:

- Blade cache OK.
- Build Vite OK.
- Sintaxis PHP OK en los archivos revisados.

Pendiente:

- Revisar visualmente `/admin` en navegador.
- Ajustar nombres de modulos si ATSA define estructura final.
- Cuando se implementen modulos operativos internos, agregar grupos nuevos:
  - Mesa de entradas.
  - Expedientes.
  - Stock y depositos.
  - Salones y reservas.
  - Reportes gerenciales.

## 27. Proximo Foco: Web Publica ATSA

Despues de pausar el admin, el foco vuelve a la pagina publica de ATSA.

Prioridades sugeridas:

1. Corregir textos rotos visibles en navbar, novedades, CTA y cards.
2. Revisar `/` home completa: hero, formacion, turismo, noticias, beneficios y CTA.
3. Revisar `/novedades` y `/novedades/{slug}`: imagenes, comentarios, texto, flechas/carousel y CTA inferior.
4. Redisenar `/afiliacion` y especialmente su impresion A4 de una sola hoja.
5. Revisar `/contacto`, `/beneficios`, `/turismo` y `/el-sindicato` en mobile.
6. Mantener la estetica Modernize, pero con identidad ATSA: azul, celeste, blanco, fotos reales y menos gradientes rojos/morados.
## 2026-04-25 - Ajuste editor de sitio admin

- Se revisó el layout de las páginas internas del editor del sitio (`/admin/editar-inicio`, `/admin/editar-gremial`, `/admin/editar-sindicato`, `/admin/editar-turismo`, `/admin/editar-contacto`).
- Se mantuvo el comportamiento correcto pedido: header principal de Filament arriba y, debajo, el sidebar custom "Sitio web ATSA" junto al contenido del formulario.
- Se ajustaron también las secciones de gestión del sitio (`/admin/posts`, `/admin/efemerides`, `/admin/descargas`, `/admin/testimonios`, `/admin/bloques-visuales`, `/admin/site-pages`, `/admin/secciones-sitio`) para que el sidebar aparezca debajo del header, no envolviendo el encabezado.
- Se dejó `.atsa-editor-page-main` como clase utilitaria para el área derecha del editor.
- Verificación realizada: `php -l` en las cinco Page classes, `php artisan view:cache` y `npm run build`.

## 2026-04-25 - Organigrama institucional real

- Se actualizó la sección `#organigrama` de la página pública `/el-sindicato`.
- Se reemplazó el organigrama genérico por datos reales de la Comisión Directiva provistos por ATSA:
  Secretario General, Secretario General Adjunto, Finanzas, Gremial, prosecretarías, secretarías, vocales titulares, vocales suplentes y Comisión Revisora de Cuentas.
- Archivo modificado: `resources/views/sindicato/index.blade.php`.
- Verificación realizada: `php -l`, `php artisan view:cache` y `npm run build`.

## 2026-04-26 - Contacto Filial Este y CTA de filiales

- Se agregó el teléfono de Filial Este: `381 5677170`.
- Se reflejó en:
  - `resources/views/filiales/index.blade.php`
  - `resources/views/home.blade.php`
  - `resources/views/contacto/index.blade.php`
  - `resources/views/delegados/index.blade.php`
  - `resources/views/layouts/app.blade.php`
- Se corrigió el badge superior del CTA de `/filiales`, que se veía como una tira blanca vacía sobre el texto "¿Necesitás más información?".

## 2026-04-26 - Separación de padrón ATSA y usuarios CENT

Motivo:

- Se detectó que el padrón de afiliados podía mostrar usuarios académicos del CENT, como alumnos o docentes.
- Regla de dominio definida: ATSA y CENT comparten proyecto, pero son áreas separadas. El padrón sindical debe listar afiliados. El CENT debe manejar alumnos, docentes, coordinadores y directivos desde `cent_role`.

Cambios realizados:

1. `app/Filament/Resources/UserResource.php`
   - El recurso de usuarios de `/admin/users` queda tratado como padrón sindical.
   - La consulta base ahora solo muestra usuarios con `role = afiliado` o con `numero_afiliado` cargado.
   - El selector de rol del padrón se redujo a `Afiliado` y `Admin ATSA`, evitando roles académicos en este módulo.

2. Recursos académicos CENT:
   - `app/Filament/Resources/CentEquivalenciaResource.php`
   - `app/Filament/Resources/CentLegajoDocumentoResource.php`
   - `app/Filament/Resources/InscripcionResource.php`
   - `app/Filament/Resources/ComisionResource.php`
   - Se eliminaron búsquedas por `role = alumno/docente`.
   - Las relaciones académicas ahora filtran por `cent_role`.

3. Paginación pública CENT:
   - Se reemplazaron `links()` crudos por `pagination::bootstrap-5` en avisos, notificaciones y novedades CENT para evitar flechas SVG gigantes o textos tipo "Showing 1 to...".

4. Textos visibles:
   - Se normalizaron acentos en vistas públicas puntuales: carnet, verificación, sindicato, registro de afiliados y layouts.

Verificación realizada:

```powershell
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe -l app\Filament\Resources\UserResource.php
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe -l app\Filament\Resources\CentEquivalenciaResource.php
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe -l app\Filament\Resources\CentLegajoDocumentoResource.php
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe -l app\Filament\Resources\InscripcionResource.php
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe -l app\Filament\Resources\ComisionResource.php
php artisan view:cache
php artisan optimize:clear
npm run build
```

Resultado:

- Sintaxis PHP OK.
- Blade cache OK.
- Vite build OK.
- El padrón visible quedó limitado a afiliados reales cargados.

Pendiente premium:

- Crear un recurso separado para "Usuarios internos ATSA" si ATSA necesita administrar recepcionistas, secretarías, responsables de filial y gerencia sin mezclarlos con el padrón.
- Implementar roles y permisos formales, idealmente con Spatie Permission, antes de avanzar con módulos grandes como stock, depósitos, salones, expedientes y mesa de entradas.
- A largo plazo, evaluar separar físicamente tablas de identidad: `users` como login general, `afiliados` como padrón sindical y tablas CENT específicas para alumnos/docentes. Por ahora la separación lógica por `role`, `numero_afiliado` y `cent_role` quedó saneada.

## 2026-04-26 - Reparación de emergencia de Resources Filament

Situación detectada:

- El sistema quedó con múltiples archivos `app/Filament/Resources/*Resource.php` en `0 bytes`.
- Eso hacía caer el panel en cadena con errores tipo:
  - `Class "App\\Filament\\Resources\\AutoridadResource" not found`
  - `Class "App\\Filament\\Resources\\BeneficioResource" not found`
- No era un problema aislado de rutas ni de caché: los resources base estaban vacíos.

Acciones tomadas:

1. Se reconstruyó manualmente `AutoridadResource`.
2. Se creó una base de rescate genérica:
   - `app/Filament/Resources/Support/GenericResource.php`
3. Se regeneraron los resources vacíos de ATSA y CENT sobre esa base común para que Filament vuelva a registrar páginas, formularios y tablas.
4. Se dejó `UserResource` con filtro explícito para que el padrón muestre solo afiliados ATSA.
5. Se validó nuevamente el arranque del panel y el registro de rutas `admin` y `cent-admin`.

Archivos clave:

- `app/Filament/Resources/Support/GenericResource.php`
- `app/Filament/Resources/AutoridadResource.php`
- `app/Filament/Resources/UserResource.php`
- Más de 30 resources regenerados en `app/Filament/Resources/`

Resultado:

- `php artisan about` volvió a responder correctamente.
- `php artisan route:list --path=admin --except-vendor` volvió a listar rutas del panel ATSA.
- `php artisan route:list --path=cent-admin --except-vendor` volvió a listar rutas del panel CENT.
- Ya no quedaron archivos vacíos en `app/`, `resources/` ni `routes/`.

Observación importante:

- Esta reparación prioriza estabilidad y arranque del sistema.
- Varios resources quedaron funcionales con estructura genérica; los módulos críticos habrá que reafinarlos después con UX, filtros, acciones y validaciones específicas.
