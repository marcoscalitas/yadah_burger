@extends('admin.dash.layouts.main')

@section('title', 'Adicionar Pedido')

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.orders.create'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h5>Adicionar Pedido</h5>
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
                                            name="customer_name" value="{{ old('customer_name') }}"
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
                                            name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}"
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
                                            <option value="1" {{ old('pickup_in_store') == '1' ? 'selected' : '' }}>
                                                Retirada na Loja
                                            </option>
                                            <option value="0" {{ old('pickup_in_store') == '0' ? 'selected' : '' }}>
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
                                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>
                                                Dinheiro
                                            </option>
                                            <option value="transfer"
                                                {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>
                                                Transferência
                                            </option>
                                            <option value="tpa" {{ old('payment_method') == 'tpa' ? 'selected' : '' }}>
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
                                <div class="col-span-12 sm:col-span-6" id="address_fields" style="display: none;">
                                    <div class="mb-1">
                                        <label class="form-label">
                                            Endereço Principal <span class="text-danger" id="address_required">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('address_1') is-invalid @enderror"
                                            name="address_1" value="{{ old('address_1') }}"
                                            placeholder="Rua, número, bairro" />
                                        @error('address_1')
                                            <div class="text-danger d-flex align-items-center mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Address 2 -->
                                <div class="col-span-12 sm:col-span-6" id="address_2_field" style="display: none;">
                                    <div class="mb-1">
                                        <label class="form-label">Complemento</label>
                                        <input type="text" class="form-control @error('address_2') is-invalid @enderror"
                                            name="address_2" value="{{ old('address_2') }}"
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
                                            value="{{ old('discount_amount', '0,00') }}" />
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
                                            placeholder="Observações sobre o pedido">{{ old('notes') }}</textarea>
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
                                    data-pc-target="#productModal" data-pc-animate="sticky-up">
                                    <i class="fas fa-plus me-2"></i>Adicionar Produto
                                </button>
                            </div>

                            <!-- Products Container -->
                            <div id="productsContainer">
                                <div id="emptyProductsMessage" class="text-center py-4 text-muted">
                                    <i class="ti ti-shopping-cart-off text-4xl mb-2 d-block"></i>
                                    <p class="mb-0">Nenhum produto adicionado ao pedido</p>
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
                                                    <span class="text-muted">Subtotal:</span>
                                                    <span id="subtotalDisplay" class="fw-medium">0,00 Kz</span>
                                                </div>
                                            </div>
                                            <div class="col-span-6 sm:col-span-4">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Desconto:</span>
                                                    <span id="discountDisplay" class="fw-medium">0,00 Kz</span>
                                                </div>
                                            </div>
                                            <div class="col-span-12 sm:col-span-4">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-primary fw-bold">Total Final:</span>
                                                    <span id="totalDisplay" class="text-primary fw-bold">0,00 Kz</span>
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
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="ti ti-arrow-left me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-2"></i>Criar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Product Selection Modal -->
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
                    <div class="grid grid-cols-1 gap-4">
                        @if (isset($categories) && $categories->count() > 0)
                            @foreach ($categories as $category)
                                @if ($category->products && $category->products->count() > 0)
                                    <div>
                                        <div class="">
                                            <div
                                                style="display: flex; align-items: center; gap: 12px; padding: 12px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid var(--bs-primary);">
                                                <div
                                                    style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;">
                                                    <img src="{{ $category->getImageUrl() }}"
                                                        alt="{{ $category->name }}"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <h5 style="margin: 0; font-weight: bold; color: #333;">
                                                    {{ $category->name }}</h5>
                                                <small
                                                    style="color: #6c757d; margin-left: auto;">{{ $category->products->count() }}
                                                    produtos disponíveis</small>
                                            </div>
                                        </div>
                                        <hr class="border-secondary-500/10 my-2 mb-6">
                                        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px;">
                                            @foreach ($category->products as $product)
                                                <!-- Product Card -->
                                                <div>
                                                    <div class="mb-3">
                                                        <div class="border rounded-2xl p-4 text-center shadow-sm hover:shadow-md transition cursor-pointer"
                                                            data-product-id="{{ $product->id }}"
                                                            data-product-name="{{ $product->name }}"
                                                            data-product-price="{{ $product->price }}"
                                                            data-category-name="{{ $category->name }}">
                                                            @if ($product->image)
                                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                                    alt="{{ $product->name }}"
                                                                    class="mx-auto mb-3 rounded"
                                                                    style="width: 120px; height: 120px; object-fit: cover;">
                                                            @else
                                                                <div class="mx-auto mb-3 rounded bg-gray-200 d-flex align-items-center justify-content-center"
                                                                    style="width: 120px; height: 120px;">
                                                                    <i class="ti ti-photo text-4xl text-gray-400"></i>
                                                                </div>
                                                            @endif
                                                            <h6 class="font-medium">{{ $product->name }}</h6>
                                                            <p class="text-primary-600 font-semibold mt-1">
                                                                {{ number_format($product->price, 2, ',', '.') }} Kz
                                                            </p>
                                                            <div class="flex items-center justify-center gap-2 mt-2">
                                                                <button type="button"
                                                                    class="w-7 h-7 rounded-lg inline-flex items-center justify-center btn-link-secondary"
                                                                    onclick="changeQuantity({{ $product->id }}, -1)">
                                                                    <i class="ti ti-minus text-sm leading-none"></i>
                                                                </button>
                                                                <span
                                                                    class="quantity-display font-semibold text-sm px-1 min-w-[20px] text-center"
                                                                    data-product-id="{{ $product->id }}">0</span>
                                                                <button type="button"
                                                                    class="w-7 h-7 rounded-lg inline-flex items-center justify-center btn-link-secondary"
                                                                    onclick="changeQuantity({{ $product->id }}, 1)">
                                                                    <i class="ti ti-plus text-sm leading-none"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="ti ti-shopping-cart-off text-6xl text-gray-400 mb-3 d-block"></i>
                                <h6 class="text-gray-600">Nenhum produto encontrado</h6>
                                <p class="text-gray-500 mb-0">Cadastre produtos para começar a criar pedidos</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer flex justify-center gap-3 border-t">
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
    <script></script>
@endsection
