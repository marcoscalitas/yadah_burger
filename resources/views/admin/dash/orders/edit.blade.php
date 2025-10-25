@extends('admin.dash.layouts.main')

@section('title', 'Editar Pedido')

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.orders.edit', [['label' => $order->order_number]]))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="orderForm">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Editar Pedido #{{ $order->order_number }}</h5>
                            <span class="badge
                                @if($order->order_status === 'p') bg-warning
                                @elseif($order->order_status === 'st') bg-info
                                @elseif($order->order_status === 'c') bg-success
                                @elseif($order->order_status === 'd') bg-primary
                                @else bg-danger
                                @endif">
                                {{ $order->getStatusName() }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Customer Information -->
                        <div class="mb-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>Informações do Cliente
                            </h6>
                            <div class="grid grid-cols-12 gap-6">
                                <!-- Customer Name -->
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-1">
                                        <label class="form-label">
                                            Nome do Cliente <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control @error('customer_name') is-invalid @enderror"
                                            name="customer_name" value="{{ old('customer_name', $order->customer_name) }}"
                                            placeholder="Digite o nome do cliente" />
                                        @error('customer_name')
                                            <div class="text-danger d-flex align-items-center mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Customer Phone -->
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-1">
                                        <label class="form-label">Telefone</label>
                                        <input type="text"
                                            class="form-control @error('customer_phone') is-invalid @enderror"
                                            name="customer_phone" id="customer_phone"
                                            value="{{ old('customer_phone', $order->customer_phone) }}"
                                            placeholder="Ex: (11) 99999-9999" />
                                        @error('customer_phone')
                                            <div class="text-danger d-flex align-items-center mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Information -->
                        <div class="mb-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-truck me-2"></i>Informações de Entrega
                            </h6>
                            <div class="grid grid-cols-12 gap-6">
                                <!-- Pickup Type -->
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-1">
                                        <label class="form-label">
                                            Tipo de Entrega <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('pickup_in_store') is-invalid @enderror"
                                            name="pickup_in_store" id="pickup_in_store">
                                            <option value="">Selecione</option>
                                            <option value="1" {{ old('pickup_in_store', $order->pickup_in_store) == '1' ? 'selected' : '' }}>
                                                Retirada na Loja
                                            </option>
                                            <option value="0" {{ old('pickup_in_store', $order->pickup_in_store) == '0' ? 'selected' : '' }}>
                                                Entrega
                                            </option>
                                        </select>
                                        @error('pickup_in_store')
                                            <div class="text-danger d-flex align-items-center mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-1">
                                        <label class="form-label">
                                            Método de Pagamento <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('payment_method') is-invalid @enderror"
                                            name="payment_method">
                                            <option value="">Selecione</option>
                                            <option value="cash" {{ old('payment_method', $order->payment_method) == 'cash' ? 'selected' : '' }}>
                                                Dinheiro
                                            </option>
                                            <option value="transfer" {{ old('payment_method', $order->payment_method) == 'transfer' ? 'selected' : '' }}>
                                                Transferência
                                            </option>
                                            <option value="tpa" {{ old('payment_method', $order->payment_method) == 'tpa' ? 'selected' : '' }}>
                                                TPA
                                            </option>
                                        </select>
                                        @error('payment_method')
                                            <div class="text-danger d-flex align-items-center mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Address 1 -->
                                <div class="col-span-12 sm:col-span-6" id="address_fields"
                                     style="{{ old('pickup_in_store', $order->pickup_in_store) ? 'display: none;' : '' }}">
                                    <div class="mb-1">
                                        <label class="form-label">
                                            Endereço Principal <span class="text-danger" id="address_required">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('address_1') is-invalid @enderror"
                                            name="address_1" value="{{ old('address_1', $order->address_1) }}"
                                            placeholder="Rua, número, bairro" />
                                        @error('address_1')
                                            <div class="text-danger d-flex align-items-center mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Address 2 -->
                                <div class="col-span-12 sm:col-span-6" id="address_2_field"
                                     style="{{ old('pickup_in_store', $order->pickup_in_store) ? 'display: none;' : '' }}">
                                    <div class="mb-1">
                                        <label class="form-label">Complemento</label>
                                        <input type="text" class="form-control @error('address_2') is-invalid @enderror"
                                            name="address_2" value="{{ old('address_2', $order->address_2) }}"
                                            placeholder="Apartamento, bloco, referência" />
                                        @error('address_2')
                                            <div class="text-danger d-flex align-items-center mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Discount -->
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-1">
                                        <label class="form-label">Desconto</label>
                                        <input type="text"
                                            class="form-control @error('discount_amount') is-invalid @enderror"
                                            name="discount_amount" id="discount_amount"
                                            value="{{ old('discount_amount', number_format($order->discount_amount, 2, ',', '.')) }}" />
                                        @error('discount_amount')
                                            <div class="text-danger d-flex align-items-center mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="col-span-12">
                                    <div class="mb-1">
                                        <label class="form-label">Observações</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3"
                                            placeholder="Observações sobre o pedido">{{ old('notes', $order->notes) }}</textarea>
                                        @error('notes')
                                            <div class="text-danger d-flex align-items-center mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="mb-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-shopping-cart me-2"></i>Produtos do Pedido
                            </h6>

                            <!-- Add Product Button -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-success" data-pc-toggle="modal"
                                    data-pc-target="#productModal">
                                    <i class="fas fa-plus me-2"></i>Adicionar Produto
                                </button>
                            </div>

                            <!-- Products Container -->
                            <div id="productsContainer" class="order-products-grid">
                                <div id="emptyProductsMessage" style="grid-column: 1 / -1; text-align: center; padding: 2rem 0; color: #6b7280; display: none;">
                                    <i class="ti ti-shopping-cart-off" style="font-size: 3rem; display: block; margin-bottom: 0.5rem;"></i>
                                    <p style="margin-bottom: 0;">Nenhum produto adicionado ao pedido</p>
                                    <small>Clique em "Adicionar Produto" para começar</small>
                                </div>
                            </div>

                            <!-- Total Display -->
                            <div class="col-span-12 mt-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="grid grid-cols-12 gap-3">
                                            <div class="col-span-6 sm:col-span-4">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted fw-bold">Subtotal:</span>
                                                    <span id="subtotalDisplay" class="fw-bold text-success">0,00 Kz</span>
                                                </div>
                                            </div>
                                            <div class="col-span-6 sm:col-span-4">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted fw-bold">Desconto:</span>
                                                    <span id="discountDisplay" class="fw-bold text-danger">0,00 Kz</span>
                                                </div>
                                            </div>
                                            <div class="col-span-12 sm:col-span-4">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted fw-bold">Total Final:</span>
                                                    <span id="totalDisplay" class="fw-bold text-success">0,00 Kz</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="col-span-12 text-end mt-4">
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary me-2">
                        <i class="ti ti-x me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-2"></i>Atualizar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Product Selection Modal (Same as create) -->
    <div id="productModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="productModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-semibold" id="productModalLabel">Selecionar Produtos</h5>
                    <button type="button" data-pc-modal-dismiss="#productModal"
                        class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Search and Filter Section -->
                    <div class="mb-4">
                        <div class="grid grid-cols-12 gap-4">
                            <!-- Search Input -->
                            <div class="col-span-12 md:col-span-8">
                                <div class="search-input-wrapper">
                                    <i class="ti ti-search search-input-icon"></i>
                                    <input type="search" id="productSearch" class="form-control search-input-with-icon"
                                        placeholder="Pesquisar produtos por nome..." autocomplete="off">
                                </div>
                            </div>

                            <!-- Category Filter -->
                            <div class="col-span-12 md:col-span-4">
                                <select id="categoryFilter" class="form-select">
                                    <option value="">Todas as Categorias</option>
                                    @if (isset($categories) && $categories->count() > 0)
                                        @foreach ($categories as $category)
                                            @if ($category->products && $category->products->count() > 0)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->name }} ({{ $category->products->count() }})
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Products List -->
                    <div class="products-list">
                        @forelse ($categories as $category)
                            @if ($category->products && $category->products->count() > 0)
                                <div class="category-section" data-category-section
                                    data-category-id="{{ $category->id }}">
                                    <!-- Category Header -->
                                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded mb-3"
                                        style="border-left: 4px solid var(--bs-primary);">
                                        <div class="category-image">
                                            <img src="{{ $category->getImageUrl() }}" alt="{{ $category->name }}"
                                                class="rounded-circle"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                        <h5 class="mb-0 fw-bold">{{ $category->name }}</h5>
                                        <small class="text-muted ms-auto">{{ $category->products->count() }} produtos
                                            disponíveis</small>
                                    </div>

                                    <!-- Products Grid -->
                                    <div class="products-grid-container mb-4">
                                        @foreach ($category->products as $product)
                                            <div class="product-card" data-product-card
                                                data-product-name="{{ strtolower($product->name) }}"
                                                data-category-id="{{ $category->id }}"
                                                data-product-id="{{ $product->id }}"
                                                data-product-price="{{ $product->price }}"
                                                data-category-name="{{ $category->name }}"
                                                data-product-image="{{ $product->image_url ? asset('storage/' . $product->image_url) : '' }}">

                                                @if ($product->image_url)
                                                    <img src="{{ asset('storage/' . $product->image_url) }}"
                                                        alt="{{ $product->name }}" class="product-image">
                                                @else
                                                    <div class="product-image-placeholder">
                                                        <i class="ti ti-photo"></i>
                                                    </div>
                                                @endif

                                                <h6 class="product-name">{{ $product->name }}</h6>
                                                <p class="product-price">{{ number_format($product->price, 2, ',', '.') }} Kz</p>

                                                <div class="quantity-controls">
                                                    <button type="button" class="btn btn-icon btn-light-secondary"
                                                        onclick="changeQuantity({{ $product->id }}, -1, event)">
                                                        <i class="ti ti-minus"></i>
                                                    </button>
                                                    <input type="number" class="quantity-input"
                                                        data-product-id="{{ $product->id }}" value="0"
                                                        min="0"
                                                        onchange="updateQuantityFromInput({{ $product->id }}, this.value)"
                                                        onclick="event.stopPropagation(); this.select()">
                                                    <button type="button" class="btn btn-icon btn-light-secondary"
                                                        onclick="changeQuantity({{ $product->id }}, 1, event)">
                                                        <i class="ti ti-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="text-center py-5">
                                <i class="ti ti-shopping-cart-off text-6xl text-gray-400 mb-3 d-block"></i>
                                <h6 class="text-gray-600">Nenhum produto encontrado</h6>
                                <p class="text-gray-500 mb-0">Cadastre produtos para começar a criar pedidos</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-pc-modal-dismiss="#productModal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="addSelectedProducts" disabled>
                        <i class="fas fa-plus me-2"></i>Adicionar Produtos (<span id="selectedCount">0</span>)
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script src="{{ asset('admin/assets/js/custom/product-modal.js') }}"></script>
    <script>
        // Carrega produtos existentes ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            @if(old('products'))
                // Se houver produtos no old() (erro de validação), usa esses
                const oldProducts = @json(old('products'));

                if (oldProducts && Object.keys(oldProducts).length > 0) {
                    // IMPORTANTE: Limpa o localStorage para evitar duplicação
                    localStorage.removeItem('order_products_draft');

                    // Limpa produtos que possam ter sido carregados
                    $('#productsContainer').find('[data-order-product-id]').remove();
                    $('#emptyProductsMessage').show();
                    selectedProducts.clear();

                    setTimeout(function() {
                        Object.keys(oldProducts).forEach(productId => {
                            const productData = oldProducts[productId];
                            const $productCard = $(`[data-product-id="${productId}"]`).first();

                            if ($productCard.length) {
                                const product = {
                                    id: parseInt(productId),
                                    name: $productCard.data('product-name') || '',
                                    price: parseFloat(productData.price) || 0,
                                    quantity: parseInt(productData.quantity) || 0,
                                    category: $productCard.closest('[data-category-section]').find('h6').text().trim() || '',
                                    image: $productCard.find('img').attr('src') || ''
                                };

                                addProductToOrder(product);

                                const $quantityInput = $(`.quantity-input[data-product-id="${productId}"]`);
                                $quantityInput.val(product.quantity);
                                $productCard.addClass('selected');
                            }
                        });

                        updateOrderTotals();
                    }, 500);
                }
            @else
                // Senão, carrega os produtos do pedido original
                const existingProducts = @json($existingProducts);

                // Adiciona cada produto existente
                existingProducts.forEach(product => {
                    addProductToOrder(product);
                });

                updateOrderTotals();
            @endif
        });
    </script>
@endsection
