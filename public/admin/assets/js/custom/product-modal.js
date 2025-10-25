/**
 * Product Modal Manager
 * Gerencia a seleção de produtos e cálculos do pedido
 */

// Estado global dos produtos selecionados
const selectedProducts = new Map();
let isInitialized = false;

/**
 * Inicializa o modal de produtos
 */
function initProductModal() {
    // Previne inicialização múltipla
    if (isInitialized) return;
    isInitialized = true;

    initSearchAndFilter();
    initQuantityControls();
    initAddProductsButton();
    initDiscountField();
    initDeliveryTypeToggle();
    initPhoneMask();
}

/**
 * Inicializa pesquisa e filtros
 */
function initSearchAndFilter() {
    const $searchInput = $('#productSearch');
    const $categoryFilter = $('#categoryFilter');

    if (!$searchInput.length || !$categoryFilter.length) return;

    $searchInput.on('input', debounce(filterProducts, 300));
    $categoryFilter.on('change', filterProducts);
}

/**
 * Filtra produtos baseado na pesquisa e categoria
 */
function filterProducts() {
    const searchTerm = $('#productSearch').val().toLowerCase().trim();
    const selectedCategory = $('#categoryFilter').val();
    const $categorySections = $('[data-category-section]');

    let visibleCount = 0;

    $categorySections.each(function () {
        const $section = $(this);
        const categoryId = $section.data('category-id');
        const categoryVisible = !selectedCategory || categoryId == selectedCategory;

        if (!categoryVisible) {
            $section.hide();
            return;
        }

        let categoryHasVisibleProducts = false;
        const $products = $section.find('[data-product-card]');

        $products.each(function () {
            const $card = $(this);
            const productName = $card.data('product-name');
            const matchesSearch = !searchTerm || productName.includes(searchTerm);

            if (matchesSearch) {
                $card.show();
                categoryHasVisibleProducts = true;
                visibleCount++;
            } else {
                $card.hide();
            }
        });

        $section.toggle(categoryHasVisibleProducts);
    });

    handleNoResults(visibleCount);
}

/**
 * Exibe mensagem quando nenhum produto é encontrado
 */
function handleNoResults(visibleCount) {
    const $modalBody = $('#productModal .modal-body');
    const $noResultsMsg = $('#noResultsMessage');

    $noResultsMsg.remove();

    if (visibleCount === 0) {
        const messageHtml = `
            <div id="noResultsMessage" class="text-center py-5">
                <i class="ti ti-search-off text-6xl text-gray-400 mb-3 d-block"></i>
                <h6 class="text-gray-600">Nenhum produto encontrado</h6>
                <p class="text-gray-500 mb-0">Tente ajustar os filtros de pesquisa</p>
            </div>
        `;
        $modalBody.append(messageHtml);
    }
}

/**
 * Limpa os filtros
 */
function clearFilters() {
    $('#productSearch').val('');
    $('#categoryFilter').val('');
    filterProducts();
}

/**
 * Inicializa controles de quantidade
 */
function initQuantityControls() {
    // Delegação de eventos APENAS para cards do modal (dentro de #productModal)
    $('#productModal').on('click', '[data-product-card]', handleProductClick);
}

/**
 * Manipula clique no produto
 */
function handleProductClick(e) {
    const $target = $(e.target);

    // Verifica se clicou em botões de quantidade, inputs ou ícones
    if ($target.closest('.btn-icon').length ||
        $target.closest('.quantity-input').length ||
        $target.closest('.quantity-controls').length ||
        $target.hasClass('btn-icon') ||
        $target.hasClass('quantity-input') ||
        $target.closest('button').length) {
        return; // Deixa a função changeQuantity ou input lidar
    }

    const $card = $(e.currentTarget);
    const productId = $card.data('product-id');

    if (!productId) return;

    changeQuantity(productId, 1);
}

/**
 * Altera quantidade do produto
 */
function changeQuantity(productId, delta, event) {
    // Previne propagação do evento para evitar duplicação
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }

    const $quantityInput = $(`.quantity-input[data-product-id="${productId}"]`);
    const $productCard = $(`[data-product-id="${productId}"]`).first();

    if (!$quantityInput.length || !$productCard.length) return;

    const currentQty = parseInt($quantityInput.val()) || 0;
    const newQty = Math.max(0, currentQty + delta);

    $quantityInput.val(newQty);

    if (newQty > 0) {
        addOrUpdateProduct(productId, newQty, $productCard);
        $productCard.addClass('selected');
    } else {
        removeProduct(productId);
        $productCard.removeClass('selected');
    }

    updateAddButton();
}

/**
 * Atualiza quantidade quando digitada no input
 */
function updateQuantityFromInput(productId, value) {
    const $quantityInput = $(`.quantity-input[data-product-id="${productId}"]`);
    const $productCard = $(`[data-product-id="${productId}"]`).first();

    const newQty = Math.max(0, parseInt(value) || 0);
    $quantityInput.val(newQty);

    if (newQty > 0) {
        addOrUpdateProduct(productId, newQty, $productCard);
        $productCard.addClass('selected');
    } else {
        removeProduct(productId);
        $productCard.removeClass('selected');
    }

    updateAddButton();
}

/**
 * Adiciona ou atualiza produto na seleção
 */
function addOrUpdateProduct(productId, quantity, $productCard) {
    const productData = {
        id: productId,
        name: $productCard.data('product-name'),
        price: parseFloat($productCard.data('product-price')),
        category: $productCard.data('category-name'),
        image: $productCard.data('product-image') || '',
        quantity: quantity
    };

    selectedProducts.set(productId, productData);
}

/**
 * Remove produto da seleção
 */
function removeProduct(productId) {
    selectedProducts.delete(productId);
}

/**
 * Inicializa botão de adicionar produtos
 */
function initAddProductsButton() {
    $('#addSelectedProducts').on('click', handleAddProducts);
}

/**
 * Manipula adição de produtos ao pedido
 */
function handleAddProducts() {
    if (selectedProducts.size === 0) return;

    selectedProducts.forEach((product) => {
        addProductToOrder(product);
    });

    const count = selectedProducts.size;

    clearSelection();
    updateOrderTotals();

    // Fecha o modal automaticamente
    $('[data-pc-modal-dismiss="#productModal"]').click();
}

/**
 * Adiciona produto ao container de pedidos
 */
function addProductToOrder(product) {
    const $container = $('#productsContainer');
    const $emptyMessage = $('#emptyProductsMessage');

    $emptyMessage.hide();

    // Verifica se produto já existe no pedido
    const existingCard = $container.find(`[data-order-product-id="${product.id}"]`);

    if (existingCard.length) {
        updateExistingProduct(existingCard, product);
        return;
    }

    const subtotal = product.price * product.quantity;
    const cardHtml = createProductCardHtml(product, subtotal);

    $container.append(cardHtml);
    addHiddenInputs(product);
}

/**
 * Cria HTML do card de produto no pedido
 */
function createProductCardHtml(product, subtotal) {
    const escapedName = escapeHtml(product.name);
    const imageHtml = product.image
        ? `<img src="${product.image}" alt="${escapedName}" class="order-product-image">`
        : `<div class="order-product-image-placeholder"><i class="ti ti-photo"></i></div>`;

    return `
        <div class="product-order-card" data-order-product-id="${product.id}" style="width: 48%; margin-bottom: 15px; display: inline-block; vertical-align: top; margin-right: 2%;">
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 16px;">
                <div style="display: flex; gap: 12px; align-items: flex-start;">
                    <!-- DIV 1: Imagem -->
                    <div style="flex-shrink: 0;">
                        ${imageHtml}
                    </div>

                    <!-- DIV 2: Informações -->
                    <div style="flex: 1; min-width: 0;">
                        <h6 style="font-weight: bold; margin: 0 0 4px 0; font-size: 14px; line-height: 1.3;">${escapedName}</h6>
                        <small style="color: #6b7280; display: block; margin-bottom: 8px; font-size: 12px;">${escapeHtml(product.category)}</small>
                        <div style="margin-bottom: 12px; font-size: 13px;">
                            <span class="quantity-badge" style="background: #f3f4f6; color: #374151; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: 500;">${product.quantity}x</span>
                            <span style="color: #6b7280; margin: 0 4px;">×</span>
                            <span style="font-weight: 500;">${formatCurrency(product.price)}</span>
                            <span style="color: #6b7280; margin: 0 4px;">=</span>
                            <span class="order-product-subtotal" style="font-weight: bold; color: #059669;">${formatCurrency(subtotal)}</span>
                        </div>
                        <div style="display: flex; gap: 6px; align-items: center;">
                            <button type="button" style="width: 32px; height: 32px; background: white; border: 1px solid #d1d5db; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 14px; color: #374151; padding: 0;" onclick="decrementOrderProduct(${product.id}, event)" title="Diminuir">
                                <i class="ti ti-minus"></i>
                            </button>
                            <input type="number" class="order-product-quantity" style="width: 50px; height: 32px; border: 1px solid #d1d5db; border-radius: 6px; text-align: center; font-size: 13px; padding: 0; -moz-appearance: textfield;" value="${product.quantity}" min="1" onclick="event.stopPropagation()" onchange="updateOrderProductQuantity(${product.id}, this.value)">
                            <button type="button" style="width: 32px; height: 32px; background: white; border: 1px solid #d1d5db; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 14px; color: #374151; padding: 0;" onclick="incrementOrderProduct(${product.id}, event)" title="Aumentar">
                                <i class="ti ti-plus"></i>
                            </button>
                            <button type="button" style="width: 32px; height: 32px; background: white; border: 1px solid #dc2626; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 14px; color: #dc2626; padding: 0; margin-left: 6px;" onclick="confirmRemoveOrderProduct(${product.id}, '${escapedName}')" title="Remover">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Atualiza produto existente no pedido
 */
function updateExistingProduct($card, product) {
    const $qtyInput = $card.find('.order-product-quantity');
    const currentQty = parseInt($qtyInput.val()) || 0;
    const newQty = currentQty + product.quantity;
    const subtotal = product.price * newQty;

    // Adiciona animação de atualização
    $card.addClass('bg-light-primary');

    setTimeout(() => {
        $qtyInput.val(newQty);
        $card.find('.order-product-subtotal').text(formatCurrency(subtotal));
        $card.find('.quantity-badge').text(newQty + 'x');

        // Atualiza hidden input
        $(`input[name="products[${product.id}][quantity]"]`).val(newQty);

        // Remove animação
        setTimeout(() => {
            $card.removeClass('bg-light-primary');
        }, 300);
    }, 150);
}

/**
 * Remove produto do pedido (sem confirmação - chamado pelo modal)
 */
function removeOrderProduct(productId) {
    const $card = $(`[data-order-product-id="${productId}"]`);

    $card.fadeOut(300, function () {
        $(this).remove();
        $(`input[name^="products[${productId}]"]`).remove();
        updateOrderTotals();
        checkEmptyProducts();
    });
}

/**
 * Confirma remoção do produto com modal
 */
function confirmRemoveOrderProduct(productId, productName) {
    // Gera ID único para o modal
    const modalId = `deleteProductModal_${productId}_${Date.now()}`;

    // Cria o HTML do modal
    const modalHtml = `
        <div id="${modalId}" class="modal fade" tabindex="-1" role="dialog"
             aria-labelledby="deleteProductModalLabel${productId}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-semibold text-danger text-lg">
                            <i class="ti ti-alert-triangle me-2"></i>Remover Produto
                        </h5>
                        <button type="button"
                            class="close-modal-btn text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="ti ti-trash text-danger mb-3" style="font-size: 3rem;"></i>
                        <h6 class="mb-2">Deseja remover este produto?</h6>
                        <p class="text-muted mb-0">
                            <strong>${escapeHtml(productName)}</strong> será removido do pedido.
                        </p>
                    </div>
                    <div class="modal-footer flex justify-end gap-3 border-t">
                        <button type="button" class="btn btn-secondary cancel-modal-btn">
                            <i class="ti ti-x me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-danger confirm-delete-btn">
                            <i class="ti ti-trash me-1"></i>Sim, Remover
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Adiciona modal ao body
    $('body').append(modalHtml);

    const $modal = $('#' + modalId);

    // Função para fechar o modal
    const closeModal = function () {
        $modal.removeClass('animate');
        setTimeout(() => {
            $modal.removeClass('show');
            $('body').removeClass('modal-open');
            $('#modaloverlay').remove();
            setTimeout(() => {
                $modal.remove();
            }, 100);
        }, 300);
    };

    // Adiciona evento ao botão de confirmar remoção
    $modal.find('.confirm-delete-btn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        removeOrderProduct(productId);
        closeModal();
    });

    // Adiciona evento aos botões de cancelar/fechar
    $modal.find('.cancel-modal-btn, .close-modal-btn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        closeModal();
    });

    // Fecha modal ao clicar fora
    $modal.on('click', function (e) {
        if ($(e.target).hasClass('modal')) {
            closeModal();
        }
    });

    // Abre o modal manualmente
    setTimeout(() => {
        // Adiciona animação
        $modal.addClass('anim-sticky-up');
        $modal.addClass('show');

        setTimeout(() => {
            $modal.addClass('animate');
        }, 100);

        // Cria overlay
        if (!$('#modaloverlay').length) {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-gray-900/20 z-[1028] backdrop-blur-sm';
            overlay.id = 'modaloverlay';
            document.body.appendChild(overlay);
            $('body').addClass('modal-open');

            // Fecha modal ao clicar no overlay
            $(overlay).on('click', closeModal);
        }
    }, 50);
}

/**
 * Incrementa quantidade do produto no pedido
 */
function incrementOrderProduct(productId, event) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }

    const $card = $(`[data-order-product-id="${productId}"]`);
    const $qtyInput = $card.find('.order-product-quantity');
    const currentQty = parseInt($qtyInput.val()) || 0;
    const newQty = currentQty + 1;

    updateOrderProductQuantity(productId, newQty);
}

/**
 * Decrementa quantidade do produto no pedido
 */
function decrementOrderProduct(productId, event) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }

    const $card = $(`[data-order-product-id="${productId}"]`);
    const $qtyInput = $card.find('.order-product-quantity');
    const currentQty = parseInt($qtyInput.val()) || 0;

    if (currentQty <= 1) {
        // Pega o nome do produto para o modal
        const productName = $card.find('h6').text();
        confirmRemoveOrderProduct(productId, productName);
        return;
    }

    const newQty = currentQty - 1;
    updateOrderProductQuantity(productId, newQty);
}

/**
 * Atualiza quantidade do produto no pedido
 */
function updateOrderProductQuantity(productId, newQty) {
    const quantity = Math.max(1, parseInt(newQty) || 1);
    const $card = $(`[data-order-product-id="${productId}"]`);
    const $qtyInput = $card.find('.order-product-quantity');
    const price = parseFloat($(`input[name="products[${productId}][price]"]`).val());
    const subtotal = price * quantity;

    $qtyInput.val(quantity);
    $card.find('.quantity-badge').text(quantity);
    $card.find('.order-product-subtotal').text(formatCurrency(subtotal));

    // Atualiza hidden input
    $(`input[name="products[${productId}][quantity]"]`).val(quantity);

    updateOrderTotals();
}

/**
 * Verifica se não há produtos e exibe mensagem
 */
function checkEmptyProducts() {
    const $container = $('#productsContainer');
    const $emptyMessage = $('#emptyProductsMessage');
    const hasProducts = $container.find('[data-order-product-id]').length > 0;

    $emptyMessage.toggle(!hasProducts);
}

/**
 * Atualiza totais do pedido
 */
function updateOrderTotals() {
    let subtotal = 0;

    $('[data-order-product-id]').each(function () {
        const $card = $(this);
        const productId = $card.data('order-product-id');
        const quantity = parseInt($card.find('.order-product-quantity').val()) || 0;
        const price = parseFloat($(`input[name="products[${productId}][price]"]`).val()) || 0;
        subtotal += price * quantity;
    });

    const discount = parseMoneyToFloat($('#discount_amount').val());
    const total = Math.max(0, subtotal - discount);

    $('#subtotalDisplay').text(formatCurrency(subtotal));
    $('#discountDisplay').text(formatCurrency(discount));
    $('#totalDisplay').text(formatCurrency(total));
}

/**
 * Limpa seleção do modal
 */
function clearSelection() {
    selectedProducts.clear();
    $('.quantity-input').val('0');
    $('.product-card').removeClass('selected');
    updateAddButton();
}

/**
 * Atualiza botão de adicionar
 */
function updateAddButton() {
    const $button = $('#addSelectedProducts');
    const count = selectedProducts.size;

    $button.prop('disabled', count === 0);
    $('#selectedCount').text(count);
}

/**
 * Inicializa campo de desconto
 */
function initDiscountField() {
    const $discountField = $('#discount_amount');

    if (!$discountField.length) return;

    $discountField.on('input', function () {
        formatMoneyInput(this);
        updateOrderTotals();
    });

    // Formata valor inicial
    formatMoneyInput($discountField[0]);
}

/**
 * Inicializa toggle de tipo de entrega
 */
function initDeliveryTypeToggle() {
    const $pickupSelect = $('#pickup_in_store');

    if (!$pickupSelect.length) return;

    $pickupSelect.on('change', toggleAddressFields);

    // Trigger inicial se houver valor old
    if ($pickupSelect.val()) {
        toggleAddressFields();
    }
}

/**
 * Alterna campos de endereço
 */
function toggleAddressFields() {
    const isPickup = $('#pickup_in_store').val() === '1';
    const $addressFields = $('#address_fields, #address_2_field');
    const $addressRequired = $('#address_required');

    if (isPickup) {
        $addressFields.hide();
        $addressRequired.hide();
    } else {
        $addressFields.show();
        $addressRequired.show();
    }
}

/**
 * Inicializa máscara de telefone
 */
function initPhoneMask() {
    const $phoneField = $('#customer_phone');

    if (!$phoneField.length) return;

    $phoneField.on('input', function () {
        let value = this.value.replace(/\D/g, '');

        if (value.length <= 9) {
            value = value.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');
        } else {
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{0,3})/, '$1 $2 $3 $4');
        }

        this.value = value.trim();
    });
}

/**
 * Formata input monetário
 */
function formatMoneyInput(input) {
    let value = input.value.replace(/\D/g, '');
    value = (parseInt(value) || 0).toString();
    value = value.padStart(3, '0');

    const intPart = value.slice(0, -2);
    const decPart = value.slice(-2);

    input.value = `${parseInt(intPart).toLocaleString('pt-BR')},${decPart}`;
}

/**
 * Converte valor monetário para float
 */
function parseMoneyToFloat(value) {
    if (!value) return 0;
    return parseFloat(value.replace(/\./g, '').replace(',', '.')) || 0;
}

/**
 * Formata valor como moeda
 */
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value) + ' Kz';
}

/**
 * Debounce helper
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Adiciona inputs hidden para envio do formulário
 */
function addHiddenInputs(product) {
    const $form = $('#orderForm');

    // Remove inputs existentes deste produto
    $form.find(`input[name^="products[${product.id}]"]`).remove();

    const inputs = `
        <input type="hidden" name="products[${product.id}][id]" value="${product.id}">
        <input type="hidden" name="products[${product.id}][quantity]" value="${product.quantity}">
        <input type="hidden" name="products[${product.id}][price]" value="${product.price}">
    `;

    $form.append(inputs);
}

/**
 * Escapa HTML para prevenir XSS
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, m => map[m]);
}

// Inicializa quando o DOM estiver pronto
$(document).ready(initProductModal);
