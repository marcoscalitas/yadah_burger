/**
 * Função global para requisições HTTP com fetch (JSON ou FormData)
 * @param {string} url - URL do endpoint
 * @param {string} method - GET, POST, PUT, DELETE
 * @param {object|FormData|null} data - Dados a enviar
 * @param {boolean} isFormData - true se data for FormData (upload de arquivos)
 * @returns {Promise<object|string>} - JSON ou texto retornado pelo backend
 */
async function requestApi(url, method = 'GET', data = null, isFormData = false) {
    // Early return: validação de URL
    if (!url) throw new Error('URL inválida');

    const headers = {};

    // Adiciona CSRF token para métodos diferentes de GET
    if (method !== 'GET') {
        // const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const token = document.querySelector('.form-with-csrf-token input[name="_token"]').value;

        if (!token) throw new Error('CSRF token não encontrado');
        headers['X-CSRF-TOKEN'] = token;
    }

    // Define corpo da requisição
    let body = null;
    if (data) {
        body = isFormData ? data : JSON.stringify(data);
        if (!isFormData) headers['Content-Type'] = 'application/json';
    }

    let response;
    try {
        response = await fetch(url, { method, headers, body });
    } catch (networkErr) {
        console.error(`[Network Error] ${method} ${url}:`, networkErr);
        throw new Error('Falha na conexão com o servidor');
    }

    // Early return: se GET sem corpo e sucesso, retorna direto
    const contentType = response.headers.get('Content-Type') || '';
    const isJson = contentType.includes('application/json');
    const responseData = isJson ? await response.json() : await response.text();

    if (!response.ok) {
        const message = isJson ? responseData.message || response.statusText : responseData || 'Erro desconhecido';
        throw new Error(message);
    }

    return responseData;
}

// GET request
async function getData(url) {
    return await requestApi(url, 'GET');
}

// POST request
async function postData(url, data, isFormData = false) {
    return await requestApi(url, 'POST', data, isFormData);
}


// PUT request
async function putData(url, data, isFormData = false) {
    return await requestApi(url, 'PUT', data, isFormData);
}

// DELETE request
async function deleteData(url, data = null, isFormData = false) {
    return await requestApi(url, 'DELETE', data, isFormData);
}

// Função de validação de imagem reutilizável
function validateImage(file, maxSizeMB = 3) {
    if (!file) {
        return { valid: false, message: 'Selecione um arquivo primeiro!' };
    }

    const maxSize = maxSizeMB * 1024 * 1024; // Convertendo MB para bytes
    if (file.size > maxSize) {
        return { valid: false, message: `Imagem maior que ${maxSizeMB}MB. Escolha outra.` };
    }

    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!allowedTypes.includes(file.type)) {
        return { valid: false, message: 'Formato inválido! Apenas PNG ou JPEG.' };
    }

    return { valid: true, message: '' };
}

// Função única para mostrar mensagens (alert)
const showMessage = (message, alertId, isSuccess = false) => {
    const alertContainer = document.getElementById(alertId);
    if (!alertContainer) return;

    alertContainer.classList.remove('alert-success', 'alert-danger');
    alertContainer.classList.add(isSuccess ? 'alert-success' : 'alert-danger');

    const iconClass = isSuccess ? 'fa-check-circle' : 'fa-exclamation-triangle';
    alertContainer.innerHTML = `<span><i class="fas ${iconClass} fa-lg me-2"></i></span> ${message}`;
    alertContainer.style.display = 'block';

    setTimeout(() => {
        $(alertContainer).fadeOut('slow');
    }, 10000);
};

// Função específica para erros
const showError = (message, alertId) => showMessage(message, alertId, false);

// Função de upload da foto
function uploadUserPhoto(url, path) {
    const profileInput = document.getElementById('profilePhotoInput');
    const saveBtn = document.getElementById('savePhotoBtn');
    const profilePreview = document.getElementById('profilePreview');
    const alertId = 'photoMessage';

    saveBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        const file = profileInput.files[0];

        // Validação da imagem
        const { valid, message } = validateImage(file);
        if (!valid) {
            profileInput.value = '';
            return showError(message, alertId);
        }

        // Envia o arquivo
        const formData = new FormData();
        formData.append('photo', file);

        try {
            const response = await postData(url, formData, true);
            profilePreview.src = response.url;
            profileInput.value = '';
            showMessage(response.message || 'Foto enviada com sucesso!', alertId, true);

            setTimeout(() => {
                window.location.href = path;
            }, 2000);

        } catch (err) {
            showError(err.message || 'Erro ao enviar foto!', alertId);
            console.error(err);
        }
    });
}

// Função de preview da imagem
function setupImagePreview(inputSelector, previewSelector) {
    const $input = $(inputSelector);
    const $previewImg = $(previewSelector);
    let currentObjectURL = null;

    if ($input.length === 0 || $previewImg.length === 0) return;

    $input.on('change', function (e) {
        const file = e.target.files && e.target.files[0];
        if (!file) return;

        // Validação da imagem
        const { valid, message } = validateImage(file);
        if (!valid) {
            showError(message, 'photoMessage');
            $input.val('');
            return;
        }

        if (currentObjectURL) URL.revokeObjectURL(currentObjectURL);
        currentObjectURL = URL.createObjectURL(file);
        $previewImg.attr('src', currentObjectURL);
    });

    $('[data-pc-modal-dismiss="#editPhotoModal"]').on('click', function () {
        if (currentObjectURL) {
            URL.revokeObjectURL(currentObjectURL);
            currentObjectURL = null;
        }
    });
}

// Settings - Change Password Validation
function validatePassword() {
    // Arrow function local para checar cada regra
    const checkRule = (val, regex, selector) => {
        const $item = $(selector);
        const $icon = $item.find('i');

        if (regex.test(val)) {
            $icon.removeClass('ti ti-circle-x text-danger').addClass('ti ti-circle-check text-success');
            $item.removeClass('text-danger').addClass('text-success');
        } else {
            $icon.removeClass('ti ti-circle-check text-success').addClass('ti ti-circle-x text-danger');
            $item.removeClass('text-success').addClass('text-danger');
        }
    };

    // Função para validar a senha em tempo real
    const validate = () => {
        const val = $('#password').val();

        // Verifica cada regra
        checkRule(val, /.{8,}/, '.min-char');
        checkRule(val, /[a-z]/, '.lower');
        checkRule(val, /[A-Z]/, '.upper');
        checkRule(val, /[0-9]/, '.number');
        checkRule(val, /[@$!%*#?&]/, '.special');

        // Bloqueia botão se algum requisito não estiver ok
        const allValid = $('.password-rules li').not('.text-success').length === 0;
        $('#submitBtn').prop('disabled', !allValid);
    };

    // Binding dos eventos
    $('#password').on('input', validate);

    $('#password').on('focus', function () {
        $(this).closest('.card').addClass('border-primary');
    }).on('blur', function () {
        $(this).closest('.card').removeClass('border-primary');
    });

    // Inicialmente desabilita o botão
    $('#submitBtn').prop('disabled', true);
}

// Toggle Promotion Price
function togglePromotionPrice() {
    const selectField = $('select[name="is_featured"]');
    const promoInput = $('input[name="promotion_price"]');
    const isFeatured = selectField.val();

    if (isFeatured === "0" || isFeatured === "") {
        promoInput.prop("disabled", true).addClass("disabled-field");
    } else {
        promoInput.prop("disabled", false).removeClass("disabled-field");
    }

    selectField.off("change blur focusin focusout").on("change blur focusin focusout", function () {
        const value = $(this).val();
        if (value === "0" || value === "") {
            promoInput.prop("disabled", true).addClass("disabled-field");
        } else {
            promoInput.prop("disabled", false).removeClass("disabled-field");
        }
    });
}

function formatPriceField(fieldId) {
    const field = $('#' + fieldId);

    field.on('input blur focusout', function () {
        let value = field.val();

        // Remove tudo que não for dígito ou vírgula
        value = value.replace(/[^\d,]/g, '');

        // Divide parte inteira e decimal
        let [intPart, decPart] = value.split(',');

        // Remove zeros desnecessários à esquerda
        intPart = intPart ? intPart.replace(/^0+(?=\d)/, '') : '';

        // Adiciona pontos a cada 3 dígitos (milhar)
        intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Limita decimais a 2 casas se houver vírgula
        if (decPart !== undefined) {
            decPart = decPart.substring(0, 2);
            value = intPart + ',' + decPart;
        } else {
            value = intPart;
        }

        field.val(value);
    });
}
