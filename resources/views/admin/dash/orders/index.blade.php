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
                            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-2"></i>Adicionar Pedido
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-3">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nº Pedido</th>
                                        <th>Cliente</th>
                                        <th>Telefone</th>
                                        <th>Tipo</th>
                                        <th>Pagamento</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Data</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                   class="text-primary fw-bold text-decoration-none">
                                                    #{{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold">{{ $order->customer_name }}</span>
                                                    <small class="text-muted">{{ $order->orderItems->count() }} itens</small>
                                                </div>
                                            </td>
                                            <td>{{ $order->customer_phone ?? '-' }}</td>
                                            <td>
                                                @if($order->pickup_in_store)
                                                    <span class="badge bg-light-info">
                                                        <i class="ti ti-shopping-bag me-1"></i>Retirada
                                                    </span>
                                                @else
                                                    <span class="badge bg-light-warning">
                                                        <i class="ti ti-truck-delivery me-1"></i>Entrega
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light-secondary">
                                                    {{ $order->getPaymentMethodName() }}
                                                </span>
                                            </td>
                                            <td class="fw-bold text-success">
                                                {{ number_format($order->total_amount, 2, ',', '.') }} Kz
                                            </td>
                                            <td>
                                                <span class="badge
                                                    @if($order->order_status === 'p') bg-warning
                                                    @elseif($order->order_status === 'st') bg-info
                                                    @elseif($order->order_status === 'c') bg-success
                                                    @elseif($order->order_status === 'd') bg-primary
                                                    @else bg-danger
                                                    @endif">
                                                    {{ $order->getStatusName() }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span>{{ $order->created_at->format('d/m/Y') }}</span>
                                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="{{ route('admin.orders.show', $order) }}"
                                                        class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary"
                                                        title="Ver detalhes">
                                                        <i class="ti ti-eye text-xl leading-none"></i>
                                                    </a>

                                                    @if($order->canBeCancelled())
                                                        <a href="{{ route('admin.orders.edit', $order) }}"
                                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary"
                                                            title="Editar">
                                                            <i class="ti ti-edit text-xl leading-none"></i>
                                                        </a>
                                                    @endif

                                                    @if(in_array($order->order_status, ['p', 'x']))
                                                        <button type="button"
                                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary text-danger"
                                                            onclick="confirmDelete('{{ $order->id }}', '{{ $order->order_number }}')"
                                                            title="Excluir">
                                                            <i class="ti ti-trash text-xl leading-none"></i>
                                                        </button>

                                                        <form id="delete-form-{{ $order->id }}"
                                                              action="{{ route('admin.orders.destroy', $order) }}"
                                                              method="POST"
                                                              style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-shopping-cart-off text-6xl text-gray-400 mb-3 d-block"></i>
                            <h6 class="text-gray-600">Nenhum pedido encontrado</h6>
                            <p class="text-gray-500 mb-3">Comece criando seu primeiro pedido</p>
                            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-2"></i>Criar Pedido
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('custom-scripts')
    <script>
        function confirmDelete(orderId, orderNumber) {
            if (confirm(`Deseja realmente excluir o pedido #${orderNumber}?\n\nEsta ação não pode ser desfeita.`)) {
                document.getElementById('delete-form-' + orderId).submit();
            }
        }
    </script>
@endsection
