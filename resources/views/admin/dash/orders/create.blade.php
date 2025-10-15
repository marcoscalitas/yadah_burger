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
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-semibold" id="productModalLabel">Selecionar Produto</h5>
                    <button type="button" data-pc-modal-dismiss="#productModal"
                        class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="grid grid-cols-12 gap-6">
                        <!-- Product Selection -->
                        <div class="col-span-12">
                            <div class="mb-3">
                                <label class="form-label">Produto <span class="text-danger">*</span></label>
                                <select class="form-select" id="modalProductSelect">
                                    <option value="">Selecione um produto</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                            data-price="{{ $product->promotion_price ?? $product->price }}"
                                            data-category="{{ $product->category->name ?? '' }}">
                                            {{ $product->name }} - {{ $product->category->name ?? 'Sem categoria' }}
                                            ({{ number_format($product->promotion_price ?? $product->price, 2, ',', '.') }}
                                            Kz)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="mb-3">
                                <label class="form-label">Quantidade <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="modalQuantity" min="1"
                                    value="1">
                            </div>
                        </div>

                        <!-- Price Display -->
                        <div class="col-span-12 sm:col-span-6">
                            <div class="mb-3">
                                <label class="form-label">Subtotal</label>
                                <input type="text" class="form-control" id="modalSubtotal" readonly value="0,00 Kz">
                            </div>
                        </div>

                        <!-- WhatsApp Message -->
                        <div class="col-span-12">
                            <div class="mb-3">
                                <label class="form-label">Mensagem para WhatsApp</label>
                                <textarea class="form-control" id="modalWhatsappMessage" rows="3"
                                    placeholder="Observações específicas do produto (ex: sem cebola, ponto da carne, etc.)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex justify-center gap-3 border-t">
                    <button type="button" class="btn btn-outline-secondary"
                        data-pc-modal-dismiss="#productModal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="addProductToOrder"
                        onclick="handleAddProduct(); return false;">
                        <i class="fas fa-plus me-2"></i>Adicionar Produto
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        let productIndex = 0;

        // Função global para adicionar produto
        function handleAddProduct() {
            const productId = $('#modalProductSelect').val();
            const productName = $('#modalProductSelect option:selected').data('name');
            const productPrice = $('#modalProductSelect option:selected').data('price');
            const productCategory = $('#modalProductSelect option:selected').data('category');
            const quantity = parseInt($('#modalQuantity').val());
            const whatsappMessage = $('#modalWhatsappMessage').val();

            if (!productId || quantity < 1) {
                alert('Por favor, selecione um produto e uma quantidade válida.');
                return false;
            }

            addProductRow(productId, productName, productPrice, productCategory, quantity, whatsappMessage);

            // Close modal - múltiplas tentativas
            try {
                $('[data-pc-modal-dismiss="#productModal"]').click();
            } catch (e) {
                $('#productModal').modal('hide');
            }

            updateTotals();
            return true;
        }

        // Funções auxiliares globais
        function formatCurrency(value) {
            return value.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' Kz';
        }

        function addProductRow(productId, productName, productPrice, productCategory, quantity, whatsappMessage) {
            const subtotal = quantity * productPrice;
            const formattedPrice = formatCurrency(productPrice);
            const formattedSubtotal = formatCurrency(subtotal);

            const productRow = `
                <div class="card mb-3 product-row" data-index="${productIndex}">
                    <div class="card-body">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-12 md:col-span-4">
                                <div>
                                    <h6 class="mb-1 font-medium">${productName}</h6>
                                    <small class="text-muted">${productCategory}</small>
                                    <input type="hidden" name="products[${productIndex}][product_id]" value="${productId}">
                                </div>
                            </div>
                            <div class="col-span-6 md:col-span-2">
                                <div class="text-center">
                                    <small class="text-muted d-block">Preço Unit.</small>
                                    <span class="fw-medium text-success">${formattedPrice}</span>
                                </div>
                            </div>
                            <div class="col-span-6 md:col-span-2">
                                <div>
                                    <label class="form-label text-sm">Qtd.</label>
                                    <input type="number" class="form-control quantity-input"
                                        name="products[${productIndex}][quantity]"
                                        value="${quantity}" min="1" data-price="${productPrice}">
                                </div>
                            </div>
                            <div class="col-span-6 md:col-span-2">
                                <div class="text-center">
                                    <small class="text-muted d-block">Subtotal</small>
                                    <span class="fw-bold text-primary subtotal-display">${formattedSubtotal}</span>
                                </div>
                            </div>
                            <div class="col-span-6 md:col-span-2 text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-product" title="Remover produto">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                        ${whatsappMessage ? `
                                            <div class="grid grid-cols-12 mt-3 pt-3 border-t">
                                                <div class="col-span-12">
                                                    <small class="text-muted fw-medium d-block mb-1">
                                                        <i class="ti ti-brand-whatsapp me-1"></i>Obs. WhatsApp:
                                                    </small>
                                                    <p class="mb-0 text-sm bg-light p-2 rounded">${whatsappMessage}</p>
                                                    <input type="hidden" name="products[${productIndex}][whatsapp_message]" value="${whatsappMessage}">
                                                </div>
                                            </div>
                                        ` : ''}
                    </div>
                </div>
            `;

            $('#productsContainer').append(productRow);
            productIndex++;

            // Hide empty message
            $('#emptyProductsMessage').hide();
        }

        function updateTotals() {
            let subtotal = 0;

            $('.quantity-input').each(function() {
                const quantity = parseInt($(this).val()) || 0;
                const price = parseFloat($(this).data('price'));
                subtotal += quantity * price;
            });

            const discountText = $('#discount_amount').val().replace(/\./g, '').replace(',', '.');
            const discount = parseFloat(discountText) || 0;
            const total = subtotal - discount;

            $('#subtotalDisplay').text(formatCurrency(subtotal));
            $('#discountDisplay').text(formatCurrency(discount));
            $('#totalDisplay').text(formatCurrency(total));
        }

        $(document).ready(function() {

            // Format price fields
            if (typeof formatPriceField === 'function') {
                formatPriceField('discount_amount');
            }

            // Phone mask
            $('#customer_phone').on('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                    if (value.length < 14) {
                        value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                    }
                }
                this.value = value;
            });

            // Toggle address fields based on pickup type
            $('#pickup_in_store').change(function() {
                const isDelivery = $(this).val() === '0';
                if (isDelivery) {
                    $('#address_fields, #address_2_field').show();
                    $('#address_required').show();
                } else {
                    $('#address_fields, #address_2_field').hide();
                    $('#address_required').hide();
                    $('input[name="address_1"], input[name="address_2"]').val('');
                }
            });

            // Initialize address fields visibility
            if ($('#pickup_in_store').val() === '0') {
                $('#address_fields, #address_2_field').show();
            }

            // Reset modal when opened - usando evento genérico
            $(document).on('click', '[data-pc-toggle="modal"][data-pc-target="#productModal"]', function() {
                setTimeout(function() {
                    $('#modalProductSelect').val('');
                    $('#modalQuantity').val(1);
                    $('#modalWhatsappMessage').val('');
                    $('#modalSubtotal').val('0,00 Kz');

                    // Re-bind direct event (caso o framework remova event listeners)
                    $('#addProductToOrder').off('click').on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handleAddProduct();
                    });
                }, 100);
            });

            // Update subtotal in modal when product or quantity changes
            $('#modalProductSelect, #modalQuantity').on('change input', function() {
                updateModalSubtotal();
            });

            function updateModalSubtotal() {
                const price = parseFloat($('#modalProductSelect option:selected').data('price')) || 0;
                const quantity = parseInt($('#modalQuantity').val()) || 0;
                const subtotal = price * quantity;
                $('#modalSubtotal').val(formatCurrency(subtotal));
            }

            // Event delegation
            $(document).on('click', '#addProductToOrder', function(e) {
                e.preventDefault();
                e.stopPropagation();
                handleAddProduct();
            });

            // Direct binding (fallback)
            $('#addProductToOrder').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                handleAddProduct();
            });

            // Keyboard support
            $(document).on('keypress', '#productModal', function(e) {
                if (e.which === 13 && e.target.id !==
                    'modalWhatsappMessage') { // Enter key, but not in textarea
                    e.preventDefault();
                    handleAddProduct();
                }
            });

            // Update discount display when changed
            $('#discount_amount').on('input', function() {
                updateTotals();
            });



            // Remove product
            $(document).on('click', '.remove-product', function() {
                $(this).closest('.product-row').remove();
                updateTotals();

                // Show empty message if no products
                if ($('#productsContainer .product-row').length === 0) {
                    $('#emptyProductsMessage').show();
                }
            });

            // Update quantity
            $(document).on('input', '.quantity-input', function() {
                const quantity = parseInt($(this).val()) || 0;
                const price = parseFloat($(this).data('price'));
                const subtotal = quantity * price;

                $(this).closest('.product-row').find('.subtotal-display').text(formatCurrency(subtotal));
                updateTotals();
            });



            // Form validation
            $('#orderForm').submit(function(e) {
                let errors = [];

                // Check if products are added
                if ($('#productsContainer .product-row').length === 0) {
                    errors.push('Adicione pelo menos um produto ao pedido');
                }

                // Check customer name
                if (!$('input[name="customer_name"]').val().trim()) {
                    errors.push('Nome do cliente é obrigatório');
                }

                // Check delivery type
                if (!$('select[name="pickup_in_store"]').val()) {
                    errors.push('Selecione o tipo de entrega');
                }

                // Check payment method
                if (!$('select[name="payment_method"]').val()) {
                    errors.push('Selecione o método de pagamento');
                }

                // Check address if delivery
                if ($('select[name="pickup_in_store"]').val() === '0' && !$('input[name="address_1"]').val()
                    .trim()) {
                    errors.push('Endereço é obrigatório para entrega');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    alert('Por favor, corrija os seguintes erros:\n\n• ' + errors.join('\n• '));
                    return false;
                }

                // Show loading state
                $(this).find('button[type="submit"]').prop('disabled', true).html(
                    '<i class="ti ti-loader-2 me-2 animate-spin"></i>Criando Pedido...');

                return true;
            });

            // Keyboard shortcuts
            $(document).keydown(function(e) {
                // Ctrl/Cmd + P to add product
                if ((e.ctrlKey || e.metaKey) && e.which === 80) {
                    e.preventDefault();
                    $('[data-pc-toggle="modal"][data-pc-target="#productModal"]').click();
                }

                // Escape to close modal
                if (e.which === 27) {
                    $('[data-pc-modal-dismiss="#productModal"]').click();
                }
            });

            // Auto-focus first input when modal opens
            $(document).on('click', '[data-pc-toggle="modal"][data-pc-target="#productModal"]', function() {
                setTimeout(function() {
                    $('#modalProductSelect').focus();
                }, 300);
            });

            // Global click listener para capturar qualquer clique no botão
            $('body').on('click', function(e) {
                if ($(e.target).is('#addProductToOrder') || $(e.target).closest('#addProductToOrder')
                    .length) {
                    e.preventDefault();
                    e.stopPropagation();
                    handleAddProduct();
                }
            });
        });
    </script>
@endsection
