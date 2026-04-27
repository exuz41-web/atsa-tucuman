<?php

use App\Http\Controllers\AfiliadosController;
use App\Http\Controllers\AfiliadoDashboardController;
use App\Http\Controllers\AfiliadoLoginController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\CarnetController;
use App\Http\Controllers\CentAlumnoController;
use App\Http\Controllers\CentAvisoController;
use App\Http\Controllers\CentClassroomController;
use App\Http\Controllers\CentController;
use App\Http\Controllers\CentDirectivoController;
use App\Http\Controllers\CentDocenteController;
use App\Http\Controllers\CentLoginController;
use App\Http\Controllers\CentMesaExamenController;
use App\Http\Controllers\CentPortalAcademicoController;
use App\Http\Controllers\CentReciboController;
use App\Http\Controllers\CentReporteController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\DelegadosController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\EfemeridesController;
use App\Http\Controllers\EscalasController;
use App\Http\Controllers\FilialesController;
use App\Http\Controllers\GremialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NovedadesController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\SindicatoController;
use App\Http\Controllers\SolicitudAfiliacionController;
use App\Http\Controllers\TurismoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/verificar/{numero_afiliado}', [CarnetController::class, 'verificar'])
    ->name('carnet.verificar');

Route::get('/admin/salir', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/admin/login');
})->name('admin.force-logout');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/el-sindicato', [SindicatoController::class, 'index'])->name('sindicato.index');
Route::get('/gremial', [GremialController::class, 'index'])->name('gremial.index');
Route::get('/afiliados', [AfiliadosController::class, 'index'])->name('afiliados.index');
Route::get('/afiliacion', [SolicitudAfiliacionController::class, 'create'])->name('afiliacion.create');
Route::post('/afiliacion', [SolicitudAfiliacionController::class, 'store'])->name('afiliacion.store');
Route::get('/afiliacion/gracias', [SolicitudAfiliacionController::class, 'thanks'])->name('afiliacion.gracias');
Route::get('/afiliacion/{token}/pdf', [SolicitudAfiliacionController::class, 'pdf'])->name('afiliacion.pdf');
Route::get('/filiales', [FilialesController::class, 'index'])->name('filiales.index');
Route::get('/novedades', [NovedadesController::class, 'index'])->name('novedades.index');
Route::get('/novedades/{slug}', [NovedadesController::class, 'show'])->name('novedades.show');
Route::post('/novedades/{slug}/comentarios', [NovedadesController::class, 'comentar'])
    ->name('novedades.comentar')
    ->middleware('throttle:5,1');
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto.index');
Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store')->middleware('throttle:5,1');
Route::get('/turismo', [TurismoController::class, 'index'])->name('turismo.index');
Route::post('/turismo/consulta', [TurismoController::class, 'consulta'])
    ->name('turismo.consulta')
    ->middleware('throttle:5,1');
Route::get('/escalas-salariales', [EscalasController::class, 'index'])->name('escalas.index');
Route::get('/delegados', [DelegadosController::class, 'index'])->name('delegados.index');
Route::get('/documentos', [DocumentosController::class, 'index'])->name('documentos.index');
Route::get('/efemerides', [EfemeridesController::class, 'index'])->name('efemerides.index');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/formacion', fn () => redirect(env('CENT_URL', url('/cent74'))))->name('formacion');

Route::prefix('cent74')->name('cent.')->group(function () {
    Route::get('/', [CentController::class, 'index'])->name('index');
    Route::get('/carreras', [CentController::class, 'carreras'])->name('carreras');
    Route::get('/carreras/{carrera:slug}', [CentController::class, 'carrera'])->name('carrera');
    Route::get('/sedes', [CentController::class, 'sedes'])->name('sedes');
    Route::get('/requisitos', [CentController::class, 'requisitos'])->name('requisitos');
    Route::get('/preguntas-frecuentes', [CentController::class, 'faq'])->name('faq');
    Route::get('/novedades', [CentController::class, 'novedades'])->name('novedades');
    Route::get('/novedades/{aviso}', [CentController::class, 'avisoShow'])->name('novedades.show');
    Route::get('/mesas-de-examen', [CentController::class, 'mesas'])->name('mesas');
    Route::get('/contacto', [CentController::class, 'contacto'])->name('contacto');
    Route::get('/preinscripcion', [CentController::class, 'preinscripcion'])->name('preinscripcion');
    Route::post('/preinscripcion', [CentController::class, 'guardarPreinscripcion'])->name('preinscripcion.guardar')->middleware('throttle:5,1');
    Route::get('/preinscripcion/consulta', [CentController::class, 'consultaPreinscripcion'])->name('preinscripcion.consulta');
    Route::post('/preinscripcion/consulta', [CentController::class, 'consultarPreinscripcion'])->name('preinscripcion.consultar')->middleware('throttle:10,1');
    Route::get('/preinscripcion/gracias/{codigo}', [CentController::class, 'gracias'])->name('preinscripcion.gracias');
    Route::post('/preinscripcion/{codigo}/documentacion', [CentController::class, 'actualizarDocumentacionPreinscripcion'])->name('preinscripcion.documentacion')->middleware('throttle:5,1');
    Route::get('/preinscripcion/{codigo}/ficha', [CentController::class, 'ficha'])->name('preinscripcion.ficha');
    Route::get('/horarios', [CentController::class, 'horarios'])->name('horarios');
    Route::get('/descargas', [CentController::class, 'descargas'])->name('descargas');
    Route::get('/permisos/verificar/{token}', [CentClassroomController::class, 'verificarPermiso'])->name('permisos.verificar');
    Route::get('/carnet/verificar/{token}', [CentClassroomController::class, 'verificarCarnet'])->name('carnet.verificar');
    Route::get('/recibos/verificar/{token}', [CentReciboController::class, 'verificar'])->name('recibos.verificar');
    Route::get('/login', [CentLoginController::class, 'show'])->name('login');
    Route::post('/login', [CentLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [CentLoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'cent.role:alumno,docente,coordinador,directivo,admin'])->group(function () {
        Route::get('/portal', [CentLoginController::class, 'portal'])->name('portal');
        Route::get('/avisos', [CentAvisoController::class, 'index'])->name('avisos');
        Route::get('/calendario', [CentPortalAcademicoController::class, 'calendario'])->name('calendario');
        Route::get('/perfil', [CentPortalAcademicoController::class, 'perfil'])->name('perfil');
        Route::post('/perfil', [CentPortalAcademicoController::class, 'actualizarPerfil'])->name('perfil.actualizar');
        Route::get('/notificaciones', [CentPortalAcademicoController::class, 'notificaciones'])->name('notificaciones');
        Route::post('/notificaciones/{notificacion}/leer', [CentPortalAcademicoController::class, 'leerNotificacion'])->name('notificaciones.leer');
    });

    Route::middleware(['auth', 'cent.role:alumno,coordinador,directivo,admin'])->group(function () {
        Route::get('/alumno/dashboard', [CentAlumnoController::class, 'dashboard'])->name('alumno.dashboard');
        Route::get('/alumno/mi-carrera', [CentAlumnoController::class, 'carrera'])->name('alumno.carrera');
        Route::get('/alumno/mis-notas', [CentAlumnoController::class, 'notas'])->name('alumno.notas');
        Route::get('/alumno/ficha-academica', [CentAlumnoController::class, 'ficha'])->name('alumno.ficha');
        Route::get('/alumno/constancia', [CentAlumnoController::class, 'constanciaPdf'])->name('alumno.constancia-pdf');
        Route::get('/alumno/ficha-academica/pdf', [CentAlumnoController::class, 'fichaPdf'])->name('alumno.ficha-pdf');
        Route::get('/alumno/mesas', [CentMesaExamenController::class, 'alumnoIndex'])->name('alumno.mesas');
        Route::post('/alumno/mesas/{mesa}/inscribir', [CentMesaExamenController::class, 'inscribirAlumno'])->name('alumno.mesas.inscribir');
        Route::get('/alumno/mesas/inscripcion/{inscripcion}/comprobante', [CentMesaExamenController::class, 'comprobante'])->name('alumno.mesas.comprobante');
        Route::get('/alumno/legajo', [CentPortalAcademicoController::class, 'legajo'])->name('alumno.legajo');
        Route::post('/alumno/legajo', [CentPortalAcademicoController::class, 'subirDocumento'])->name('alumno.legajo.subir');
        Route::get('/alumno/cuotas', [CentPortalAcademicoController::class, 'cuotas'])->name('alumno.cuotas');
        Route::post('/alumno/cuotas/{cuota}/comprobante', [CentPortalAcademicoController::class, 'subirComprobante'])->name('alumno.cuotas.comprobante');
        Route::get('/alumno/recibos/{recibo}/pdf', [CentReciboController::class, 'pdf'])->name('alumno.recibos.pdf');
        Route::get('/alumno/aula', [CentClassroomController::class, 'aulaAlumno'])->name('alumno.aula');
        Route::post('/alumno/trabajos/{trabajo}/entregar', [CentClassroomController::class, 'entregarTrabajo'])->name('alumno.trabajos.entregar');
        Route::get('/alumno/permisos', [CentClassroomController::class, 'permisosAlumno'])->name('alumno.permisos');
        Route::post('/alumno/permisos/{mesa}/solicitar', [CentClassroomController::class, 'solicitarPermiso'])->name('alumno.permisos.solicitar');
        Route::get('/alumno/permisos/{permiso}/pdf', [CentClassroomController::class, 'permisoPdf'])->name('alumno.permisos.pdf');
        Route::get('/alumno/carnet', [CentClassroomController::class, 'carnetAlumno'])->name('alumno.carnet');
        Route::get('/alumno/carnet/pdf', [CentClassroomController::class, 'carnetPdf'])->name('alumno.carnet.pdf');
    });

    Route::middleware(['auth', 'cent.role:docente,coordinador,directivo,admin'])->group(function () {
        Route::get('/docente/dashboard', [CentDocenteController::class, 'dashboard'])->name('docente.dashboard');
        Route::get('/docente/aula', [CentClassroomController::class, 'aulaDocente'])->name('docente.aula');
        Route::post('/docente/aula/clases', [CentClassroomController::class, 'guardarClaseDocente'])->name('docente.aula.clases');
        Route::post('/docente/aula/materiales', [CentClassroomController::class, 'guardarMaterialDocente'])->name('docente.aula.materiales');
        Route::post('/docente/aula/trabajos', [CentClassroomController::class, 'guardarTrabajoDocente'])->name('docente.aula.trabajos');
        Route::get('/docente/comisiones', [CentDocenteController::class, 'comisiones'])->name('docente.comisiones');
        Route::get('/docente/mesas', [CentMesaExamenController::class, 'docenteIndex'])->name('docente.mesas');
        Route::get('/docente/mesas/{mesa}', [CentMesaExamenController::class, 'docenteShow'])->name('docente.mesas.show');
        Route::post('/docente/mesas/{mesa}/resultados', [CentMesaExamenController::class, 'guardarResultados'])->name('docente.mesas.resultados');
        Route::get('/docente/comisiones/{comision}/planilla', [CentDocenteController::class, 'planilla'])->name('docente.planilla');
        Route::post('/docente/comisiones/{comision}/planilla', [CentDocenteController::class, 'guardarPlanilla'])->name('docente.planilla.guardar');
        Route::get('/docente/comisiones/{comision}/planilla/pdf', [CentDocenteController::class, 'descargarActa'])->name('docente.planilla.pdf');
        Route::get('/docente/comisiones/{comision}/asistencia', [CentDocenteController::class, 'asistencia'])->name('docente.asistencia');
        Route::post('/docente/comisiones/{comision}/asistencia', [CentDocenteController::class, 'guardarAsistencia'])->name('docente.asistencia.guardar');
        Route::get('/docente/alumnos/{alumno}/ficha-pdf', [CentDocenteController::class, 'fichaAlumnoPdf'])->name('docente.alumnos.ficha-pdf');
        Route::post('/docente/comisiones/{comision}/planilla/cerrar', [CentDocenteController::class, 'cerrarActa'])->name('docente.planilla.cerrar');
        Route::post('/docente/comisiones/{comision}/notas', [CentDocenteController::class, 'cargarNota'])->name('docente.notas.guardar');
    });

    Route::middleware(['auth', 'cent.role:coordinador,directivo,admin'])->group(function () {
        Route::post('/docente/comisiones/{comision}/planilla/aprobar', [CentDocenteController::class, 'aprobarActa'])->name('docente.planilla.aprobar');
        Route::post('/docente/comisiones/{comision}/planilla/reabrir', [CentDocenteController::class, 'reabrirActa'])->name('docente.planilla.reabrir');
        Route::get('/directivo/dashboard', [CentDirectivoController::class, 'dashboard'])->name('directivo.dashboard');
        Route::get('/directivo/alumnos', [CentDirectivoController::class, 'alumnos'])->name('directivo.alumnos');
        Route::get('/directivo/alumnos/{alumno}/constancia', [CentDirectivoController::class, 'constanciaAlumno'])->name('directivo.alumnos.constancia');
        Route::get('/directivo/alumnos/{alumno}/ficha-pdf', [CentDirectivoController::class, 'fichaAlumnoPdf'])->name('directivo.alumnos.ficha-pdf');
        Route::get('/directivo/docentes', [CentDirectivoController::class, 'docentes'])->name('directivo.docentes');
        Route::get('/directivo/comisiones', [CentDirectivoController::class, 'comisiones'])->name('directivo.comisiones');
        Route::get('/directivo/comisiones/crear', [CentDirectivoController::class, 'crearComision'])->name('directivo.comisiones.crear');
        Route::post('/directivo/comisiones', [CentDirectivoController::class, 'guardarComision'])->name('directivo.comisiones.guardar');
        Route::get('/directivo/comisiones/{comision}/editar', [CentDirectivoController::class, 'editarComision'])->name('directivo.comisiones.editar');
        Route::put('/directivo/comisiones/{comision}', [CentDirectivoController::class, 'actualizarComision'])->name('directivo.comisiones.actualizar');
        Route::post('/directivo/comisiones/{comision}/inscribir', [CentDirectivoController::class, 'inscribirAlumno'])->name('directivo.comisiones.inscribir');
        Route::get('/directivo/actas', [CentDirectivoController::class, 'actas'])->name('directivo.actas');
        Route::get('/directivo/actas-mesas', [CentDirectivoController::class, 'actasMesas'])->name('directivo.actas-mesas');
        Route::get('/directivo/actas-mesas/{mesa}/pdf', [CentDirectivoController::class, 'actaMesaPdf'])->name('directivo.actas-mesas.pdf');
        Route::post('/directivo/actas-mesas/{mesa}/aprobar', [CentDirectivoController::class, 'aprobarActaMesa'])->name('directivo.actas-mesas.aprobar');
        Route::post('/directivo/actas-mesas/{mesa}/reabrir', [CentDirectivoController::class, 'reabrirActaMesa'])->name('directivo.actas-mesas.reabrir');
        Route::get('/directivo/reportes', [CentDirectivoController::class, 'reportes'])->name('directivo.reportes');
        Route::get('/directivo/reportes/pdf', [CentReporteController::class, 'reportesPdf'])->name('directivo.reportes.pdf');
        Route::get('/directivo/reportes/alumnos.csv', [CentReporteController::class, 'alumnosCsv'])->name('directivo.reportes.alumnos');
        Route::get('/directivo/reportes/cuotas.csv', [CentReporteController::class, 'cuotasCsv'])->name('directivo.reportes.cuotas');
        Route::get('/directivo/reportes/preinscripciones.csv', [CentReporteController::class, 'preinscripcionesCsv'])->name('directivo.reportes.preinscripciones');
        Route::get('/directivo/reportes/actas.csv', [CentReporteController::class, 'actasCsv'])->name('directivo.reportes.actas');
        Route::get('/directivo/reportes/mesas.csv', [CentReporteController::class, 'mesasCsv'])->name('directivo.reportes.mesas');
        Route::get('/directivo/reportes/finales.csv', [CentReporteController::class, 'finalesCsv'])->name('directivo.reportes.finales');
    });
});

Route::redirect('/login', '/afiliados/login')->name('login');
Route::get('/afiliados/login', [AfiliadoLoginController::class, 'showLogin'])->name('afiliados.login');
Route::post('/afiliados/login', [AfiliadoLoginController::class, 'login'])->name('afiliados.login.submit');
Route::get('/afiliados/registro', [AfiliadoLoginController::class, 'showRegister'])->name('afiliados.register');
Route::post('/afiliados/registro', [AfiliadoLoginController::class, 'register'])->name('afiliados.register.submit');
Route::get('/afiliados/recuperar-password', [AfiliadoLoginController::class, 'showForgotPassword'])->name('afiliados.password.request');
Route::post('/afiliados/recuperar-password', [AfiliadoLoginController::class, 'sendResetLink'])->name('afiliados.password.email');
Route::get('/afiliados/reset-password/{token}', [AfiliadoLoginController::class, 'showResetPassword'])->name('afiliados.password.reset');
Route::post('/afiliados/reset-password', [AfiliadoLoginController::class, 'resetPassword'])->name('afiliados.password.update');
Route::post('/afiliados/logout', [AfiliadoLoginController::class, 'logout'])->name('afiliados.logout');

Route::prefix('afiliados')->middleware(['auth', 'role:afiliado,admin'])->group(function () {
    Route::get('/dashboard', [AfiliadoDashboardController::class, 'index'])->name('afiliados.dashboard');
    Route::get('/mi-carnet', [CarnetController::class, 'index'])->name('afiliado.carnet');
    Route::get('/mi-carnet/descargar', [CarnetController::class, 'descargar'])->name('afiliado.carnet.descargar');
    Route::get('/mi-carnet/imagen', [CarnetController::class, 'descargarImagen'])->name('afiliado.carnet.imagen');
    Route::get('/mi-carnet/wallet-data', [CarnetController::class, 'walletData'])->name('afiliado.carnet.wallet');
    Route::post('/mi-carnet/foto', [CarnetController::class, 'subirFoto'])->name('afiliado.carnet.foto');
    Route::get('/mis-datos', [AfiliadoDashboardController::class, 'datos'])->name('afiliados.datos');
    Route::post('/mis-datos', [AfiliadoDashboardController::class, 'actualizarDatos'])->name('afiliados.datos.actualizar');
    Route::get('/mis-pedidos', [AfiliadoDashboardController::class, 'pedidos'])->name('afiliados.pedidos');
    Route::get('/nuevo-pedido', [AfiliadoDashboardController::class, 'nuevoPedido'])->name('afiliados.pedidos.nuevo');
    Route::post('/nuevo-pedido', [AfiliadoDashboardController::class, 'guardarPedido'])->name('afiliados.pedidos.guardar');
    Route::get('/mis-consultas', [AfiliadoDashboardController::class, 'consultas'])->name('afiliados.consultas');
    Route::post('/nueva-consulta', [AfiliadoDashboardController::class, 'guardarConsulta'])->name('afiliados.consultas.guardar');
    Route::get('/beneficios', [AfiliadoDashboardController::class, 'beneficios'])->name('afiliados.beneficios');
    Route::get('/beneficios/{beneficio:slug}/solicitar', [AfiliadoDashboardController::class, 'solicitarBeneficio'])->name('afiliados.beneficios.solicitar');
    Route::post('/beneficios/{beneficio:slug}/solicitar', [AfiliadoDashboardController::class, 'guardarSolicitudBeneficio'])->name('afiliados.beneficios.guardar');
    Route::get('/descargas', [AfiliadoDashboardController::class, 'descargas'])->name('afiliados.descargas');
    Route::get('/mi-testimonio', [AfiliadoDashboardController::class, 'testimonio'])->name('afiliados.testimonio');
    Route::post('/mi-testimonio', [AfiliadoDashboardController::class, 'guardarTestimonio'])->name('afiliados.testimonio.guardar');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/panel/carnets', [PanelController::class, 'carnets'])->name('panel.carnets');
    Route::post('/panel/carnets/{id}/emitir', [PanelController::class, 'emitirCarnet'])->name('panel.carnets.emitir');
    Route::post('/panel/carnets/{id}/revocar', [PanelController::class, 'revocarCarnet'])->name('panel.carnets.revocar');
    Route::get('/panel/backups/{filename}', [BackupController::class, 'download'])->name('panel.backups.download');
});
