@php
    $afiliadosCount = \App\Models\User::count(); // Ajustar según lógica de roles si existe
    $pedidosCount = \App\Models\Pedido::count();
    $consultasCount = \App\Models\Consulta::count();
    $postsCount = \App\Models\Post::count();
    $filialesCount = \App\Models\Filial::count();
    $carrerasCount = \App\Models\Carrera::count();
@endphp

<x-filament-panels::page>
    <div class="modernize-dashboard">
        <!-- SECCIÓN 1 — Cards de acceso rápido -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <!-- Card 1 -->
            <div class="card bg-primary-subtle border-0 shadow-none text-center p-4 rounded-4">
                <div class="mb-2">
                    <img src="https://bootstrapdemos.adminmart.com/modernize/dist/assets/images/svgs/icon-user-male.svg" alt="afiliados" width="40" class="mx-auto">
                </div>
                <h5 class="fw-semibold fs-5 mb-1">{{ $afiliadosCount }}</h5>
                <p class="text-primary mb-0 fs-2 fw-semibold">Afiliados</p>
            </div>
            <!-- Card 2 -->
            <div class="card bg-warning-subtle border-0 shadow-none text-center p-4 rounded-4">
                <div class="mb-2">
                    <img src="https://bootstrapdemos.adminmart.com/modernize/dist/assets/images/svgs/icon-briefcase.svg" alt="pedidos" width="40" class="mx-auto">
                </div>
                <h5 class="fw-semibold fs-5 mb-1">{{ $pedidosCount }}</h5>
                <p class="text-warning mb-0 fs-2 fw-semibold">Pedidos</p>
            </div>
            <!-- Card 3 -->
            <div class="card bg-success-subtle border-0 shadow-none text-center p-4 rounded-4">
                <div class="mb-2">
                    <img src="https://bootstrapdemos.adminmart.com/modernize/dist/assets/images/svgs/icon-mailbox.svg" alt="consultas" width="40" class="mx-auto">
                </div>
                <h5 class="fw-semibold fs-5 mb-1">{{ $consultasCount }}</h5>
                <p class="text-success mb-0 fs-2 fw-semibold">Consultas</p>
            </div>
            <!-- Card 4 -->
            <div class="card bg-danger-subtle border-0 shadow-none text-center p-4 rounded-4">
                <div class="mb-2">
                    <img src="https://bootstrapdemos.adminmart.com/modernize/dist/assets/images/svgs/icon-favorites.svg" alt="noticias" width="40" class="mx-auto">
                </div>
                <h5 class="fw-semibold fs-5 mb-1">{{ $postsCount }}</h5>
                <p class="text-danger mb-0 fs-2 fw-semibold">Noticias</p>
            </div>
            <!-- Card 5 -->
            <div class="card bg-info-subtle border-0 shadow-none text-center p-4 rounded-4">
                <div class="mb-2">
                    <img src="https://bootstrapdemos.adminmart.com/modernize/dist/assets/images/svgs/icon-speech-bubble.svg" alt="filiales" width="40" class="mx-auto">
                </div>
                <h5 class="fw-semibold fs-5 mb-1">{{ $filialesCount }}</h5>
                <p class="text-info mb-0 fs-2 fw-semibold">Filiales</p>
            </div>
            <!-- Card 6 -->
            <div class="card bg-secondary-subtle border-0 shadow-none text-center p-4 rounded-4">
                <div class="mb-2">
                    <img src="https://bootstrapdemos.adminmart.com/modernize/dist/assets/images/svgs/icon-connect.svg" alt="carreras" width="40" class="mx-auto">
                </div>
                <h5 class="fw-semibold fs-5 mb-1">{{ $carrerasCount }}</h5>
                <p class="text-secondary mb-0 fs-2 fw-semibold">Carreras</p>
            </div>
        </div>

        <!-- SECCIÓN 2 — Gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Gráfico Principal -->
            <div class="lg:col-span-2 card border-0 shadow-sm p-4 rounded-4">
                <h5 class="card-title fw-semibold mb-4">Actividad mensual</h5>
                <canvas id="revenueChart" style="height: 300px;"></canvas>
            </div>
            <!-- Gráfico Donut y Lista -->
            <div class="flex flex-col gap-6">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <h5 class="card-title fw-semibold mb-4">Distribución de pedidos</h5>
                    <canvas id="orderDonut" style="height: 200px;"></canvas>
                </div>
                <div class="card border-0 shadow-sm p-4 rounded-4 flex-grow">
                    <h5 class="card-title fw-semibold mb-4">Últimas consultas</h5>
                    <ul class="list-none p-0">
                        @foreach(\App\Models\Consulta::latest()->take(5)->get() as $consulta)
                            <li class="flex items-center gap-3 mb-3 pb-3 border-b border-gray-100 last:border-0">
                                <div class="w-2 h-2 rounded-full bg-primary"></div>
                                <div>
                                    <h6 class="mb-0 fs-3 fw-semibold">{{ $consulta->nombre }}</h6>
                                    <p class="text-muted mb-0 fs-2">{{ Str::limit($consulta->mensaje, 30) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 3 — Tabla reciente -->
        <div class="card border-0 shadow-sm p-4 rounded-4">
            <h5 class="card-title fw-semibold mb-4">Pedidos Recientes</h5>
            <div class="table-responsive">
                <table class="table align-middle text-nowrap mb-0">
                    <thead>
                        <tr class="text-muted fw-semibold">
                            <th class="ps-0">Usuario</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="border-top">
                        @foreach(\App\Models\Pedido::latest()->take(10)->get() as $pedido)
                            <tr>
                                <td class="ps-0">
                                    <div class="flex items-center">
                                        <div class="me-3">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pedido->user->name ?: 'User') }}" class="rounded-circle" width="35" height="35" alt="user">
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">{{ $pedido->user->name ?: 'Anónimo' }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $pedido->tipo ?: 'General' }}</td>
                                <td>
                                    @php
                                        $color = match($pedido->estado) {
                                            'pendiente' => 'bg-warning-subtle text-warning',
                                            'completado' => 'bg-success-subtle text-success',
                                            'cancelado' => 'bg-danger-subtle text-danger',
                                            default => 'bg-primary-subtle text-primary',
                                        };
                                    @endphp
                                    <span class="badge {{ $color }} rounded-3 fw-semibold fs-2">{{ ucfirst($pedido->estado) }}</span>
                                </td>
                                <td>{{ $pedido->created_at->format('d M, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart 1: Revenue Updates
        const ctx = document.getElementById('revenueChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [{
                    label: 'Pedidos',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: '#5d87ff',
                    borderRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Chart 2: Order Donut
        const ctx2 = document.getElementById('orderDonut');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Salud', 'Gremial', 'Otros'],
                datasets: [{
                    data: [30, 45, 25],
                    backgroundColor: ['#5d87ff', '#13deb9', '#ffae1f'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>

<style>
    .modernize-dashboard .card {
        border-radius: 12px;
    }
    .bg-primary-subtle { background: #ecf2ff !important; }
    .bg-warning-subtle { background: #fef5e5 !important; }
    .bg-success-subtle { background: #e6fffa !important; }
    .bg-danger-subtle { background: #fbf2ef !important; }
    .bg-info-subtle { background: #ebf3fe !important; }
    .bg-secondary-subtle { background: #e8f7ff !important; }
    
    .text-primary { color: #5d87ff !important; }
    .text-warning { color: #ffae1f !important; }
    .text-success { color: #13deb9 !important; }
    .text-danger { color: #fa896b !important; }
    .text-info { color: #539bff !important; }
    .text-secondary { color: #49beff !important; }

    .table thead th {
        font-size: 14px;
        border-bottom: 0;
    }
    .table tbody td {
        border-bottom: 1px solid #f1f1f1;
        padding: 15px 0;
    }
</style>
