@extends('admin.dash.layouts.main')

@section('title', 'Pedidos')

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.orders.index'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <h5 class="mb-3 sm:mb-0">Lista de pedidos</h5>
                        <div>
                            {{-- <a href="{{ route('admin.orders.trashed') }}" class="btn btn-outline-secondary mr-1">
                                Ver pedidos eliminados
                            </a> --}}
                            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-2"></i>Adicionar Pedido
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
                            <div class="datatable-container">
                                <table class="table table-hover datatable-table" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true" style="width: 5%;">
                                                <button class="datatable-sorter">#</button>
                                            </th>
                                            <th data-sortable="true" style="width: 10%;">
                                                <button class="datatable-sorter">Nº Pedido</button>
                                            </th>
                                            <th data-sortable="true" style="width: 18%;">
                                                <button class="datatable-sorter">Cliente</button>
                                            </th>
                                            <th data-sortable="true" style="width: 12%;">
                                                <button class="datatable-sorter">Telefone</button>
                                            </th>
                                            <th data-sortable="true" style="width: 10%;">
                                                <button class="datatable-sorter">Tipo</button>
                                            </th>
                                            <th data-sortable="true" style="width: 10%;">
                                                <button class="datatable-sorter">Total</button>
                                            </th>
                                            <th data-sortable="true" style="width: 10%;">
                                                <button class="datatable-sorter">Estado</button>
                                            </th>
                                            <th data-sortable="true" style="width: 10%;">
                                                <button class="datatable-sorter">Data</button>
                                            </th>
                                            <th data-sortable="true" style="width: 6%;">
                                                <button class="datatable-sorter">Ação</button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $index => $order)
                                            <tr data-index="{{ $index }}" data-id="{{ $order->id }}">
                                                <td>{{ $index + 1 }}</td>

                                                <td>
                                                    <a href="{{ route('admin.orders.show', $order) }}"
                                                        class="text-primary fw-bold text-decoration-none">
                                                        #{{ $order->order_number }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold">{{ $order->customer_name }}</span>
                                                        <small class="text-muted">
                                                            {{ $order->orderItems->count() }} itens
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>{{ getFormattedPhone($order->customer_phone) }}</td>
                                                <td>
                                                    @if ($order->pickup_in_store)
                                                        <span class="badge bg-light-info">
                                                            <i class="ti ti-shopping-bag me-1"></i>Retirada
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light-warning">
                                                            <i class="ti ti-truck-delivery me-1"></i>Entrega
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{!! getFormattedCurrency($order->total_amount) !!}</td>
                                                <td>{!! getStatusBadge($order->order_status) !!}</td>
                                                <td>{!! getFormattedDateTime($order->created_at) !!}</td>
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <a href="{{ route('admin.orders.show', $order) }}"
                                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary"
                                                            title="Ver detalhes">
                                                            <i class="ti ti-eye text-xl leading-none"></i>
                                                        </a>

                                                        @if ($order->canBeCancelled())
                                                            <a href="{{ route('admin.orders.edit', $order) }}"
                                                                class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary"
                                                                title="Editar">
                                                                <i class="ti ti-edit text-xl leading-none"></i>
                                                            </a>
                                                        @endif

                                                        @if (in_array($order->order_status, ['p', 'x']))
                                                            <button type="button"
                                                                class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary text-danger"
                                                                title="Excluir">
                                                                <i class="ti ti-trash text-xl leading-none"></i>
                                                            </button>

                                                            <form id="delete-form-{{ $order->id }}"
                                                                action="{{ route('admin.orders.destroy', $order) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal de Confirmação de Exclusão -->

                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-8">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <i class="ti ti-tag text-6xl text-gray-300 mb-4"></i>
                                                        <h5 class="text-gray-500 mb-2">Nenhum pedido encontrado.</h5>
                                                        <p class="text-gray-400 mb-4">Ainda não há pedidos cadastrados
                                                            no
                                                            sistema.</p>
                                                        <a href="{{ route('admin.orders.create') }}"
                                                            class="btn btn-primary">
                                                            <i class="ti ti-plus me-2"></i>Adicionar primeiro pedido
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('custom-scripts')
    <script></script>
@endsection
