@extends('admin.dash.layouts.main')

@section('title', 'Pedidos Eliminados')

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.orders.trashed'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <h5 class="mb-3 sm:mb-0">Lista de pedidos eliminados</h5>
                        <div>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                                <i class="ti ti-arrow-left me-2"></i>Ver pedidos ativos
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-3">
                    @if ($orders->count() === 0)
                        <div class="text-center py-5">
                            <i class="ti ti-trash text-6xl text-muted mb-3"></i>
                            <p class="text-muted">Nenhum pedido eliminado encontrado</p>
                        </div>
                    @else
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
                                                    <button class="datatable-sorter">Total</button>
                                                </th>
                                                <th data-sortable="true" style="width: 10%;">
                                                    <button class="datatable-sorter">Estado</button>
                                                </th>
                                                <th data-sortable="true" style="width: 10%;">
                                                    <button class="datatable-sorter">Eliminado em</button>
                                                </th>
                                                <th data-sortable="true" style="width: 6%;">
                                                    <button class="datatable-sorter">Ação</button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $index => $order)
                                                <tr data-index="{{ $index }}" data-id="{{ $order->id }}">
                                                    <td>{{ $index + 1 }}</td>

                                                    <td>
                                                        <span class="text-muted fw-bold">
                                                            #{{ $order->order_number }}
                                                        </span>
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

                                                    <td>{!! getFormattedCurrency($order->total_amount) !!}</td>

                                                    <td>{!! getStatusBadge($order->order_status) !!}</td>

                                                    <td>
                                                        <small class="text-muted">
                                                            {!! getFormattedDateTime($order->deleted_at) !!}
                                                        </small>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            {{-- Restaurar --}}
                                                            <button type="button"
                                                                class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-success"
                                                                data-pc-toggle="modal"
                                                                data-pc-target="#restoreOrderModal{{ $order->id }}"
                                                                title="Restaurar pedido">
                                                                <i class="ti ti-refresh text-xl leading-none"></i>
                                                            </button>

                                                            {{-- Excluir Permanentemente --}}
                                                            <button type="button"
                                                                class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-danger"
                                                                data-pc-toggle="modal"
                                                                data-pc-target="#deleteOrderModal{{ $order->id }}"
                                                                data-pc-animate="sticky-up"
                                                                title="Excluir permanentemente">
                                                                <i class="ti ti-trash text-xl leading-none"></i>
                                                            </button>
                                                        </div>

                                                        {{-- Modal de Confirmação de Restauração --}}
                                                        <div class="modal fade modal-animate" id="restoreOrderModal{{ $order->id }}"
                                                            tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Confirmar restauração</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="text-center">
                                                                            <i class="ti ti-refresh text-success" style="font-size: 3rem;"></i>
                                                                            <h4 class="mt-3">Restaurar Pedido</h4>
                                                                            <p class="text-muted">
                                                                                Tem certeza que deseja restaurar o pedido
                                                                                <strong>#{{ $order->order_number }}</strong>?
                                                                            </p>
                                                                            <p class="text-muted">
                                                                                O pedido voltará para a lista de pedidos ativos.
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Cancelar</button>
                                                                        <form method="POST"
                                                                            action="{{ route('admin.orders.restore', $order->id) }}"
                                                                            style="display: inline;">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <button type="submit" class="btn btn-success">
                                                                                <i class="ti ti-refresh me-2"></i>Sim, restaurar
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Modal de Confirmação de Exclusão Permanente --}}
                                                        <div class="modal fade modal-animate" id="deleteOrderModal{{ $order->id }}"
                                                            tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Confirmar exclusão permanente</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="text-center">
                                                                            <i class="ti ti-alert-triangle text-warning" style="font-size: 3rem;"></i>
                                                                            <h4 class="mt-3">Atenção!</h4>
                                                                            <p class="text-muted">
                                                                                Tem certeza que deseja excluir permanentemente o pedido
                                                                                <strong>#{{ $order->order_number }}</strong>?
                                                                            </p>
                                                                            <p class="text-danger">
                                                                                <strong>Esta ação não pode ser desfeita!</strong>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Cancelar</button>
                                                                        <form method="POST"
                                                                            action="{{ route('admin.orders.force.destroy', $order->id) }}"
                                                                            style="display: inline;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-danger">
                                                                                Sim, excluir permanentemente
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Paginação --}}
                        @if ($orders->hasPages())
                            <div class="mt-4">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
