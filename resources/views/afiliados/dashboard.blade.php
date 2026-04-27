@extends('layouts.afiliado')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
@php
    $dashboardUser = auth()->user();
    $dashboardFotoUrl = $dashboardUser ? \App\Support\CarnetSupport::fotoUrl($dashboardUser) : null;
    $dashboardIniciales = $dashboardUser ? \App\Support\CarnetSupport::initials($dashboardUser->name) : 'A';
    $carnetVencido = $dashboardUser?->carnet_vencimiento && $dashboardUser->carnet_vencimiento->lt(now()->startOfDay());
    $carnetActivo = (bool) ($dashboardUser?->carnet_activo && ! $carnetVencido);
@endphp

<div class="row g-4">
    <div class="col-12">
        <div class="portal-card overflow-hidden">
            <div class="p-4 p-lg-5" style="background: linear-gradient(135deg, #ffffff 0%, #eef7ff 100%);">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">Portal privado</span>
                        <h1 class="fw-bolder mb-2">Hola, {{ $dashboardUser->name }}</h1>
                        <p class="text-muted fs-4 mb-0" style="max-width: 760px;">
                            Desde acá podés consultar tu carnet digital, cargar solicitudes, revisar consultas,
                            descargar documentación y mantener tus datos actualizados.
                        </p>
                    </div>
                    <div class="col-lg-4">
                        <div class="portal-card p-4 bg-white">
                            <div class="d-flex align-items-center gap-3">
                                <span class="d-inline-grid overflow-hidden rounded-circle bg-primary-subtle text-primary fw-bolder flex-shrink-0" style="width:92px;height:92px;place-items:center;font-size:32px;border:6px solid #eef7ff;">
                                    @if ($dashboardFotoUrl)
                                        <img src="{{ $dashboardFotoUrl }}" alt="Foto de {{ $dashboardUser->name }}" class="w-100 h-100 object-fit-cover">
                                    @else
                                        {{ $dashboardIniciales }}
                                    @endif
                                </span>
                                <div class="min-w-0">
                                    <p class="text-muted fw-semibold mb-1">N° de afiliado</p>
                                    <h5 class="fw-bolder text-truncate mb-2">{{ $dashboardUser->numero_afiliado ?: 'Sin emitir' }}</h5>
                                    <span class="badge rounded-pill {{ $carnetActivo ? 'bg-success' : 'bg-warning' }} px-3 py-2">
                                        {{ $carnetActivo ? 'Carnet activo' : ($carnetVencido ? 'Carnet vencido' : 'Carnet pendiente') }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('afiliado.carnet') }}" class="btn btn-primary w-100 mt-4 shadow-none">
                                <i class="ti ti-id me-2"></i>Ver mi carnet
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ([
        ['icon' => 'ti-clipboard-list', 'value' => $pedidosPendientes, 'label' => 'Pedidos pendientes', 'class' => 'warning'],
        ['icon' => 'ti-message-dots', 'value' => $consultas, 'label' => 'Consultas realizadas', 'class' => 'primary'],
        ['icon' => 'ti-download', 'value' => $documentos, 'label' => 'Documentos disponibles', 'class' => 'success'],
        ['icon' => 'ti-bell', 'value' => $notificaciones->whereNull('read_at')->count(), 'label' => 'Novedades nuevas', 'class' => 'info'],
    ] as $stat)
        <div class="col-md-6 col-xl-3">
            <div class="portal-card p-4 h-100">
                <span class="portal-icon bg-light-{{ $stat['class'] }} text-{{ $stat['class'] }} mb-3">
                    <i class="ti {{ $stat['icon'] }}"></i>
                </span>
                <h3 class="fw-bolder mb-1">{{ $stat['value'] }}</h3>
                <p class="text-muted fw-semibold mb-0">{{ $stat['label'] }}</p>
            </div>
        </div>
    @endforeach

    {{-- Panel de notificaciones --}}
    @if ($notificaciones->isNotEmpty())
    <div class="col-12">
        <div class="portal-card p-4">
            <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="fw-bolder mb-0">Novedades</h4>
                    <span class="badge bg-danger rounded-pill">{{ $notificaciones->whereNull('read_at')->count() }}</span>
                </div>
            </div>
            <div class="vstack gap-2">
                @foreach ($notificaciones as $notif)
                    @php
                        $data    = $notif->data;
                        $titulo  = $data['title'] ?? 'Notificación';
                        $cuerpo  = $data['body'] ?? '';
                        $color   = $data['color'] ?? 'primary';
                        $leida   = ! is_null($notif->read_at);
                        $borde   = match($color) {
                            'success' => 'border-success',
                            'danger'  => 'border-danger',
                            'warning' => 'border-warning',
                            'info'    => 'border-info',
                            default   => 'border-primary',
                        };
                        $iconColor = match($color) {
                            'success' => 'text-success',
                            'danger'  => 'text-danger',
                            'warning' => 'text-warning',
                            'info'    => 'text-info',
                            default   => 'text-primary',
                        };
                    @endphp
                    <div class="d-flex align-items-start gap-3 rounded-3 border-start border-3 {{ $borde }} ps-3 py-2 {{ $leida ? 'opacity-75' : 'bg-light' }}">
                        <i class="ti ti-bell fs-5 mt-1 {{ $iconColor }} flex-shrink-0"></i>
                        <div class="min-w-0">
                            <p class="fw-bolder mb-0 {{ $leida ? 'text-muted' : '' }}">{{ $titulo }}</p>
                            @if ($cuerpo)
                                <p class="text-muted mb-0 fs-2">{{ $cuerpo }}</p>
                            @endif
                            <p class="text-muted mb-0 fs-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        @if (! $leida)
                            <span class="badge bg-primary rounded-pill ms-auto flex-shrink-0">Nueva</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="col-xl-8">
        <div class="portal-card h-100 p-4">
            <div class="d-flex align-items-center justify-content-between gap-3 mb-4">
                <div>
                    <h4 class="fw-bolder mb-1">Mis últimos pedidos</h4>
                    <p class="text-muted mb-0">Seguimiento de tus solicitudes recientes.</p>
                </div>
                <a href="{{ route('afiliados.pedidos') }}" class="btn btn-outline-primary shadow-none">Ver todos</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                        <th class="text-muted fw-semibold">Trámite</th>
                        <th class="text-muted fw-semibold">Fecha</th>
                        <th class="text-muted fw-semibold">Estado</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($pedidosRecientes as $pedido)
                        @php
                            $badge = match ($pedido->estado) {
                                'pendiente'              => 'bg-warning',
                                'en_revision'            => 'bg-info',
                                'aprobado'               => 'bg-primary',
                                'entregado', 'completado'=> 'bg-success',
                                'rechazado'              => 'bg-danger',
                                default                  => 'bg-secondary',
                            };
                            $estadoLabel = [
                                'pendiente'   => 'Pendiente',
                                'en_revision' => 'En revisión',
                                'aprobado'    => 'Aprobado',
                                'entregado'   => 'Entregado',
                                'completado'  => 'Completado',
                                'rechazado'   => 'Rechazado',
                            ][$pedido->estado] ?? ucfirst(str_replace('_', ' ', $pedido->estado));
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="portal-icon" style="width:44px;height:44px;font-size:20px;">
                                        <i class="ti ti-file-description"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-bolder mb-1 text-capitalize">{{ str_replace('_', ' ', $pedido->tipo) }}</h6>
                                        <p class="text-muted mb-0 fs-2">Ticket #{{ str_pad((string) $pedido->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $pedido->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $badge }} rounded-pill px-3 py-2">
                                    {{ $estadoLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <i class="ti ti-folder-open text-muted fs-9 d-block mb-2"></i>
                                <h6 class="fw-bolder mb-1">Todavía no cargaste pedidos</h6>
                                <p class="text-muted mb-3">Cuando hagas una solicitud, vas a verla en esta tabla.</p>
                                <a href="{{ route('afiliados.pedidos.nuevo') }}" class="btn btn-primary shadow-none">Nueva solicitud</a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="portal-card h-100 p-4">
            <h4 class="fw-bolder mb-4">Novedades gremiales</h4>
            <div class="vstack gap-4">
                @forelse ($novedades as $post)
                    <a href="{{ route('novedades.show', $post->slug) }}" class="d-flex align-items-start gap-3 text-decoration-none">
                        <span class="portal-icon" style="width:44px;height:44px;font-size:20px;">
                            <i class="ti ti-news"></i>
                        </span>
                        <span class="d-block min-w-0">
                            <span class="d-block fw-bolder text-dark text-truncate">{{ \Illuminate\Support\Str::limit($post->title ?: $post->titulo, 54) }}</span>
                            <span class="d-block text-muted fs-2">{{ $post->published_at?->diffForHumans() ?: 'Publicado' }}</span>
                        </span>
                    </a>
                @empty
                    <div class="text-center py-4">
                        <i class="ti ti-news-off text-muted fs-9 d-block mb-2"></i>
                        <p class="text-muted mb-0">No hay novedades gremiales publicadas.</p>
                    </div>
                @endforelse
            </div>
            <a href="{{ route('novedades.index') }}" class="btn btn-outline-primary w-100 mt-4 shadow-none">Ver novedades</a>
        </div>
    </div>

    <div class="col-12">
        <div class="portal-card p-4">
            <h4 class="fw-bolder mb-4">Accesos rápidos</h4>
            <div class="row g-3">
                @foreach ([
                    ['Mi carnet', route('afiliado.carnet'), 'ti-id'],
                    ['Nueva solicitud', route('afiliados.pedidos.nuevo'), 'ti-circle-plus'],
                    ['Mis consultas', route('afiliados.consultas'), 'ti-message-dots'],
                    ['Descargas', route('afiliados.descargas'), 'ti-download'],
                    ['Mi testimonio', route('afiliados.testimonio'), 'ti-quote'],
                ] as [$label, $href, $icon])
                    <div class="col-md-6 col-xl">
                        <a href="{{ $href }}" class="portal-card p-3 d-flex align-items-center gap-3 text-decoration-none h-100 shadow-none">
                            <span class="portal-icon" style="width:44px;height:44px;font-size:20px;"><i class="ti {{ $icon }}"></i></span>
                            <span class="fw-bolder text-dark">{{ $label }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
