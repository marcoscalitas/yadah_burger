@extends('admin.dash.layouts.main')

@section('title', 'Detalhes do Pedido')

@section('breadcrumb')
    @include(
        'admin.dash.components.breadcrumb',
        getBreadcrumb('admin.orders.show', [['label' => $order->order_number]]))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
        <!-- Order Header -->
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <div>
                            <h4 class="mb-1">Pedido #{{ $order->order_number }}</h4>
                            <p class="text-muted mb-0">
                                <i class="ti ti-calendar me-1"></i>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-2"></i>Voltar
                            </a>
                            <!-- Botão WhatsApp -->
                            <a href="{{ $order->getWhatsAppLink() }}" target="_blank" class="btn btn-success">
                                <i class="ti ti-brand-whatsapp me-2"></i>Enviar WhatsApp
                            </a>
                            @if ($order->canBeCancelled())
                                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-2"></i>Editar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="col-span-12 lg:col-span-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="ti ti-info-circle me-2"></i>Status do Pedido</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update.status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">Status Atual</label>
                            <select name="order_status" class="form-select"
                                {{ in_array($order->order_status, ['c', 'd']) ? 'disabled' : '' }}>
                                <option value="p" {{ $order->order_status === 'p' ? 'selected' : '' }}>
                                    Pendente
                                </option>
                                <option value="st" {{ $order->order_status === 'st' ? 'selected' : '' }}>
                                    Iniciado
                                </option>
                                <option value="c" {{ $order->order_status === 'c' ? 'selected' : '' }}>
                                    Concluído
                                </option>
                                <option value="d" {{ $order->order_status === 'd' ? 'selected' : '' }}>
                                    Entregue
                                </option>
                                <option value="x" {{ $order->order_status === 'x' ? 'selected' : '' }}>
                                    Cancelado
                                </option>
                            </select>
                        </div>

                        @if (!in_array($order->order_status, ['c', 'd']))
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-refresh me-2"></i>Atualizar Status
                            </button>
                        @endif
                    </form>

                    <div class="mt-4 pt-3 border-t border-theme-border">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-muted">Status:</span>
                            <span>{!! getStatusBadge($order->order_status) !!}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-muted">Pagamento:</span>
                            <span class="fw-bold">{{ $order->getPaymentMethodName() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="col-span-12 lg:col-span-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="ti ti-user me-2"></i>Informações do Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 sm:col-span-6">
                            <div class="mb-1">
                                <label class="form-label text-muted">Nome</label>
                                <p class="fw-bold mb-0">{{ $order->customer_name }}</p>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <div class="mb-1">
                                <label class="form-label text-muted">Telefone</label>
                                <p class="fw-bold mb-0">{{ $order->customer_phone ?? 'Não informado' }}</p>
                            </div>
                        </div>
                        <div class="col-span-12">
                            <div class="mb-1">
                                <label class="form-label text-muted">Tipo de Entrega</label>
                                <p class="fw-bold mb-0">
                                    @if ($order->pickup_in_store)
                                        <i class="ti ti-shopping-bag me-1"></i>Retirada na Loja
                                    @else
                                        <i class="ti ti-truck-delivery me-1"></i>Entrega
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if (!$order->pickup_in_store)
                            <div class="col-span-12">
                                <div class="mb-1">
                                    <label class="form-label text-muted">Endereço</label>
                                    <p class="fw-bold mb-0">{{ $order->address_1 }}</p>
                                    @if ($order->address_2)
                                        <p class="text-muted mb-0">{{ $order->address_2 }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if ($order->notes)
                            <div class="col-span-12">
                                <div class="mb-1">
                                    <label class="form-label text-muted">Observações</label>
                                    <p class="mb-0">{{ $order->notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="ti ti-shopping-cart me-2"></i>Produtos do Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center">Quantidade</th>
                                    <th class="text-end">Preço Unit.</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                @if ($item->product && $item->product->image_url)
                                                    <img src="{{ $item->product->getImageUrl() }}"
                                                        alt="{{ $item->product->name }}" class="rounded"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="rounded bg-light flex items-center justify-center"
                                                        style="width: 50px; height: 50px;">
                                                        <i class="ti ti-photo text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">
                                                        {{ $item->product->name ?? 'Produto não disponível' }}</h6>
                                                    @if ($item->product && $item->product->category)
                                                        <small
                                                            class="text-muted">{{ $item->product->category->name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light-secondary">{{ $item->quantity }}x</span>
                                        </td>
                                        <td class="text-end">{{ number_format($item->unit_price, 2, ',', '.') }} Kz</td>
                                        <td class="text-end fw-bold text-success">
                                            {{ number_format($item->subtotal, 2, ',', '.') }} Kz
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Totals -->
                    <div class="grid grid-cols-12 gap-6 mt-4">
                        <div class="col-span-12 md:col-span-6 lg:col-span-4 ml-auto">
                            <div class="card bg-light-secondary">
                                <div class="card-body">
                                    <div class="flex justify-between items-center mb-2">
                                        <span>Subtotal:</span>
                                        <span class="fw-bold">{{ number_format($order->subtotal, 2, ',', '.') }} Kz</span>
                                    </div>
                                    @if ($order->discount_amount > 0)
                                        <div class="flex justify-between items-center mb-2 text-danger">
                                            <span>Desconto:</span>
                                            <span
                                                class="fw-bold">-{{ number_format($order->discount_amount, 2, ',', '.') }}
                                                Kz</span>
                                        </div>
                                    @endif
                                    <hr class="my-2">
                                    <div class="flex justify-between items-center">
                                        <span class="fw-bold">Total:</span>
                                        <span class="fw-bold text-success h5 mb-0">
                                            {{ number_format($order->total_amount, 2, ',', '.') }} Kz
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="ti ti-info-circle me-2"></i>Informações Adicionais</h5>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 sm:col-span-6">
                            <div class="mb-1">
                                <label class="form-label text-muted">Criado por</label>
                                <p class="fw-bold mb-0">{{ $order->createdBy->name ?? 'Sistema' }}</p>
                                <p class="text-muted small mb-0">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        @if ($order->updated_at != $order->created_at)
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label text-muted">Última atualização</label>
                                    <p class="fw-bold mb-0">{{ $order->updatedBy->name ?? 'Sistema' }}</p>
                                    <p class="text-muted small mb-0">{{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Button (only for pending/cancelled orders) -->
        @if (in_array($order->order_status, ['p', 'x']))
            <div class="col-span-12">
                <div class="card border-danger">
                    <div class="card-body">
                        <div class="sm:flex items-center justify-between">
                            <div>
                                <h6 class="text-danger mb-1">Excluir Pedido</h6>
                                <p class="text-muted mb-0">Esta ação não pode ser desfeita.</p>
                            </div>
                            <button type="button" class="btn btn-danger" data-pc-toggle="modal"
                                data-pc-target="#deleteOrderModal">
                                <i class="ti ti-trash me-2"></i>Excluir Pedido
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- [ Main Content ] end -->

    <!-- Delete Confirmation Modal -->
    @if (in_array($order->order_status, ['p', 'x']))
        <div id="deleteOrderModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            <i class="ti ti-alert-triangle me-2"></i>Confirmar Exclusão
                        </h5>
                        <button type="button" data-pc-modal-dismiss="#deleteOrderModal"
                            class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="ti ti-trash text-danger mb-3" style="font-size: 3rem;"></i>
                        <h6 class="mb-2">Deseja realmente excluir este pedido?</h6>
                        <p class="text-muted mb-0">
                            Pedido <strong>#{{ $order->order_number }}</strong> será excluído permanentemente.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-pc-modal-dismiss="#deleteOrderModal">
                            Cancelar
                        </button>
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="ti ti-trash me-2"></i>Sim, Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
