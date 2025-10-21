/**
 * Layout Settings - Funcionalidades customizadas para o painel de configurações
 */

document.addEventListener('DOMContentLoaded', function() {

    // Evitar múltiplas inicializações
    if (window.layoutSettingsInitialized) {
        return;
    }
    window.layoutSettingsInitialized = true;

    // Funcionalidade para Theme Layout (main layout)
    const themeLayoutButtons = document.querySelectorAll('.theme-main-layout .preset-btn');
    themeLayoutButtons.forEach(button => {
        // Remove listeners existentes para evitar duplicação
        button.removeEventListener('click', handleThemeLayoutClick);
        button.addEventListener('click', handleThemeLayoutClick);
    });

    function handleThemeLayoutClick(e) {
        e.preventDefault();

        // Remove active class from all buttons
        const allButtons = document.querySelectorAll('.theme-main-layout .preset-btn');
        allButtons.forEach(btn => btn.classList.remove('active'));

        // Add active class to clicked button
        this.classList.add('active');

        // Get layout value
        const layoutValue = this.getAttribute('data-value');

        // Apply layout change
        if (typeof main_layout_change === 'function') {
            main_layout_change(layoutValue);
        }

        // Store in localStorage
        localStorage.setItem('layout', layoutValue);

        console.log('Theme layout changed to:', layoutValue);
    }

    // Funcionalidade melhorada para Custom Theme (preset colors)
    const presetColorButtons = document.querySelectorAll('.preset-color a');
    presetColorButtons.forEach(button => {
        // Remove listeners existentes
        button.removeEventListener('click', handlePresetColorClick);
        button.addEventListener('click', handlePresetColorClick);
    });

    function handlePresetColorClick(e) {
        e.preventDefault();

        // Remove active class from all buttons
        const allButtons = document.querySelectorAll('.preset-color a');
        allButtons.forEach(btn => btn.classList.remove('active'));

        // Add active class to clicked button
        this.classList.add('active');

        // Get preset value
        const presetValue = this.getAttribute('data-value');

        // Apply preset change
        if (typeof preset_change === 'function') {
            preset_change(presetValue);
        }

        // Store in localStorage
        localStorage.setItem('preset', presetValue);

        console.log('Preset changed to:', presetValue);
    }

    // Funcionalidade para Theme Mode (Light/Dark/Auto)
    const themeModeButtons = document.querySelectorAll('.pc-dark .preset-btn');
    themeModeButtons.forEach(button => {
        // Remove listeners existentes
        button.removeEventListener('click', handleThemeModeClick);
        button.addEventListener('click', handleThemeModeClick);
    });

    function handleThemeModeClick(e) {
        e.preventDefault();

        // Remove active class from all buttons
        const allButtons = document.querySelectorAll('.pc-dark .preset-btn');
        allButtons.forEach(btn => btn.classList.remove('active'));

        // Add active class to clicked button
        this.classList.add('active');

        // Get theme value
        const themeValue = this.getAttribute('data-value');

        // Apply theme change based on value
        if (themeValue === 'true') {
            if (typeof layout_change === 'function') {
                layout_change('light');
            }
            localStorage.setItem('theme', 'light');
            console.log('Theme changed to: light');
        } else if (themeValue === 'false') {
            if (typeof layout_change === 'function') {
                layout_change('dark');
            }
            localStorage.setItem('theme', 'dark');
            console.log('Theme changed to: dark');
        } else if (themeValue === 'default') {
            if (typeof layout_change_default === 'function') {
                layout_change_default();
            }
            localStorage.removeItem('theme');
            console.log('Theme changed to: auto');
        }
    }

    // Aguardar que todas as funções estejam disponíveis antes de fazer overrides
    setTimeout(() => {
        setupFunctionOverrides();
        loadSavedSettings();
    }, 100);
});

/**
 * Carrega configurações salvas do localStorage
 */
function loadSavedSettings() {
    console.log('🔄 Carregando configurações salvas do localStorage...');

    // Load theme
    const theme = localStorage.getItem('theme');
    if (theme && typeof layout_change === 'function') {
        console.log('📱 Aplicando tema salvo:', theme);
        layout_change(theme);
        updateThemeModeButtons(theme);
    }

    // Load preset
    const preset = localStorage.getItem('preset');
    if (preset && typeof preset_change === 'function') {
        console.log('🎨 Aplicando preset salvo:', preset);
        preset_change(preset);
        updatePresetButtons(preset);
    }

    // Load layout
    const layout = localStorage.getItem('layout');
    if (layout && typeof main_layout_change === 'function') {
        console.log('📐 Aplicando layout salvo:', layout);
        main_layout_change(layout);
        updateLayoutButtons(layout);
    }

    // Load contrast
    const contrast = localStorage.getItem('contrast');
    if (contrast && typeof layout_theme_contrast_change === 'function') {
        console.log('⚫ Aplicando contraste salvo:', contrast);
        layout_theme_contrast_change(contrast);
    }

    // Load caption
    const caption = localStorage.getItem('caption');
    if (caption && typeof layout_caption_change === 'function') {
        console.log('📝 Aplicando caption salvo:', caption);
        layout_caption_change(caption);
    }

    // Load direction
    const direction = localStorage.getItem('direction');
    if (direction && typeof layout_rtl_change === 'function') {
        console.log('🔄 Aplicando direção salva:', direction);
        layout_rtl_change(direction);
    }

    // Load container
    const container = localStorage.getItem('container');
    if (container && typeof change_box_container === 'function') {
        console.log('📦 Aplicando container salvo:', container);
        change_box_container(container);
    }

    console.log('✅ Configurações carregadas com sucesso!');
}/**
 * Atualiza botões do Theme Mode
 */
function updateThemeModeButtons(theme) {
    const buttons = document.querySelectorAll('.pc-dark .preset-btn');
    buttons.forEach(btn => btn.classList.remove('active'));

    let targetValue = 'true'; // light by default
    if (theme === 'dark') targetValue = 'false';

    const targetButton = document.querySelector(`.pc-dark .preset-btn[data-value="${targetValue}"]`);
    if (targetButton) {
        targetButton.classList.add('active');
    }
}

/**
 * Atualiza botões de Preset
 */
function updatePresetButtons(preset) {
    const buttons = document.querySelectorAll('.preset-color a');
    buttons.forEach(btn => btn.classList.remove('active'));

    const targetButton = document.querySelector(`.preset-color a[data-value="${preset}"]`);
    if (targetButton) {
        targetButton.classList.add('active');
    }
}

/**
 * Atualiza botões de Layout
 */
function updateLayoutButtons(layout) {
    const buttons = document.querySelectorAll('.theme-main-layout .preset-btn');
    buttons.forEach(btn => btn.classList.remove('active'));

    const targetButton = document.querySelector(`.theme-main-layout .preset-btn[data-value="${layout}"]`);
    if (targetButton) {
        targetButton.classList.add('active');
    }
}

/**
 * Configurar overrides das funções para salvar no localStorage
 */
function setupFunctionOverrides() {
    console.log('🔧 Configurando overrides das funções...');

    // Override preset_change
    if (typeof window.preset_change === 'function' && !window.preset_change._overridden) {
        const originalPresetChange = window.preset_change;
        window.preset_change = function(preset) {
            originalPresetChange(preset);
            localStorage.setItem('preset', preset);
            console.log('💾 Preset salvo:', preset);
        };
        window.preset_change._overridden = true;
    }

    // Override layout_theme_contrast_change
    if (typeof window.layout_theme_contrast_change === 'function' && !window.layout_theme_contrast_change._overridden) {
        const originalLayoutThemeContrastChange = window.layout_theme_contrast_change;
        window.layout_theme_contrast_change = function(contrast) {
            originalLayoutThemeContrastChange(contrast);
            localStorage.setItem('contrast', contrast);
            console.log('💾 Contraste salvo:', contrast);
        };
        window.layout_theme_contrast_change._overridden = true;
    }

    // Override layout_caption_change
    if (typeof window.layout_caption_change === 'function' && !window.layout_caption_change._overridden) {
        const originalLayoutCaptionChange = window.layout_caption_change;
        window.layout_caption_change = function(caption) {
            originalLayoutCaptionChange(caption);
            localStorage.setItem('caption', caption);
            console.log('💾 Caption salvo:', caption);
        };
        window.layout_caption_change._overridden = true;
    }

    // Override layout_rtl_change
    if (typeof window.layout_rtl_change === 'function' && !window.layout_rtl_change._overridden) {
        const originalLayoutRtlChange = window.layout_rtl_change;
        window.layout_rtl_change = function(direction) {
            originalLayoutRtlChange(direction);
            localStorage.setItem('direction', direction);
            console.log('💾 Direção salva:', direction);
        };
        window.layout_rtl_change._overridden = true;
    }

    // Override change_box_container
    if (typeof window.change_box_container === 'function' && !window.change_box_container._overridden) {
        const originalChangeBoxContainer = window.change_box_container;
        window.change_box_container = function(container) {
            originalChangeBoxContainer(container);
            localStorage.setItem('container', container);
            console.log('💾 Container salvo:', container);
        };
        window.change_box_container._overridden = true;
    }

    // Override main_layout_change
    if (typeof window.main_layout_change === 'function' && !window.main_layout_change._overridden) {
        const originalMainLayoutChange = window.main_layout_change;
        window.main_layout_change = function(layout) {
            originalMainLayoutChange(layout);
            localStorage.setItem('layout', layout);
            console.log('💾 Layout salvo:', layout);
        };
        window.main_layout_change._overridden = true;
    }

    console.log('✅ Overrides configurados com sucesso!');
}

/**
 * Função para forçar o carregamento das configurações
 */
window.forceLoadSettings = function() {
    console.log('🔧 Forçando carregamento das configurações...');

    // Aguardar um pouco para garantir que as funções estão disponíveis
    setTimeout(() => {
        setupFunctionOverrides();
        loadSavedSettings();
        console.log('✅ Configurações recarregadas manualmente!');
    }, 100);
};

/**
 * Função de debug para verificar o estado atual
 */
window.debugLayoutSettings = function() {
    console.log('🔍 DEBUG - Estado atual das configurações:');
    console.log('='.repeat(50));

    // Verificar localStorage
    console.log('📦 LocalStorage:');
    const keys = ['theme', 'preset', 'layout', 'contrast', 'caption', 'direction', 'container'];
    keys.forEach(key => {
        const value = localStorage.getItem(key);
        console.log(`   ${key}: ${value || 'não definido'}`);
    });

    // Verificar funções
    console.log('\n⚙️ Funções disponíveis:');
    const functions = ['layout_change', 'preset_change', 'main_layout_change', 'layout_theme_contrast_change', 'layout_caption_change', 'layout_rtl_change', 'change_box_container'];
    functions.forEach(func => {
        const exists = typeof window[func] === 'function';
        const overridden = window[func] && window[func]._overridden;
        console.log(`   ${func}: ${exists ? '✅ Existe' : '❌ Não existe'} ${overridden ? '(Override ativo)' : ''}`);
    });

    // Verificar HTML attributes
    console.log('\n🏗️ Atributos HTML atuais:');
    const html = document.documentElement;
    console.log(`   data-pc-theme: ${html.getAttribute('data-pc-theme')}`);
    console.log(`   data-pc-layout: ${html.getAttribute('data-pc-layout')}`);
    console.log(`   data-pc-theme_contrast: ${html.getAttribute('data-pc-theme_contrast')}`);
    console.log(`   data-pc-sidebar-caption: ${html.getAttribute('data-pc-sidebar-caption')}`);
    console.log(`   data-pc-direction: ${html.getAttribute('data-pc-direction')}`);
    console.log(`   class: ${html.className}`);

    console.log('='.repeat(50));
};

/**
 * Função melhorada para mudança de tema com caminhos corretos
 */
function improved_layout_change(theme) {
    document.getElementsByTagName("html")[0].setAttribute("data-pc-theme", theme);

    // Update logo paths with correct Laravel asset paths
    const isDark = theme === 'dark';
    dark_flag = isDark;

    // Use base URL do Laravel para os caminhos
    const baseUrl = window.location.origin;
    const logoPath = isDark
        ? `${baseUrl}/admin/assets/images/logo-white.svg`
        : `${baseUrl}/admin/assets/images/logo-dark.svg`;

    // Update all logo elements
    function updateLogo(selector) {
        const element = document.querySelector(selector);
        if (element) {
            element.setAttribute("src", logoPath);
        }
    }

    updateLogo(".pc-sidebar .m-header .logo-lg");
    updateLogo(".navbar-brand .logo-lg");
    updateLogo(".auth-main.v1 .auth-sidefooter img");
    updateLogo(".footer-top .footer-logo");

    // Update theme buttons
    const defaultBtn = document.querySelector('.theme-layout .btn[data-value="default"]');
    if (defaultBtn) defaultBtn.classList.remove("active");

    const activeBtn = document.querySelector(`.theme-layout .btn[data-value='${isDark ? "false" : "true"}']`);
    const currentActive = document.querySelector(".theme-layout .btn.active");
    if (currentActive) currentActive.classList.remove("active");
    if (activeBtn) activeBtn.classList.add("active");
}

// Override da função original
window.layout_change = improved_layout_change;

/**
 * Reset Layout - Limpa todas as configurações
 */
document.addEventListener('DOMContentLoaded', function() {
    const resetButton = document.querySelector('#layoutreset');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            e.preventDefault();

            // Clear all localStorage settings
            const keysToRemove = ['theme', 'preset', 'layout', 'contrast', 'caption', 'direction', 'container'];
            keysToRemove.forEach(key => localStorage.removeItem(key));

            // Reset to defaults
            layout_change('light');
            preset_change('preset-1');
            main_layout_change('vertical');
            layout_theme_contrast_change('false');
            layout_caption_change('true');
            layout_rtl_change('false');
            change_box_container('false');

            // Show success message
            console.log('Layout settings reset to defaults');
        });
    }
});

/**
 * Função de diagnóstico para testar todas as funcionalidades
 */
window.testLayoutSettings = function() {
    console.log('=== TESTE DE FUNCIONALIDADES DO LAYOUT ===');

    // Teste 1: Verificar se todas as funções existem
    const functions = [
        'layout_change',
        'layout_theme_contrast_change',
        'layout_caption_change',
        'layout_rtl_change',
        'preset_change',
        'main_layout_change',
        'change_box_container'
    ];

    console.log('1. Verificando funções disponíveis:');
    functions.forEach(func => {
        const exists = typeof window[func] === 'function';
        console.log(`   ${func}: ${exists ? '✅ OK' : '❌ ERRO'}`);
    });

    // Teste 2: Verificar elementos do DOM
    console.log('\n2. Verificando elementos do DOM:');
    const selectors = [
        '.theme-layout .btn',
        '.theme-contrast .btn',
        '.theme-nav-caption .btn',
        '.theme-direction .btn',
        '.preset-color a',
        '.theme-main-layout .preset-btn',
        '.theme-container .btn'
    ];

    selectors.forEach(selector => {
        const elements = document.querySelectorAll(selector);
        console.log(`   ${selector}: ${elements.length > 0 ? `✅ ${elements.length} elementos` : '❌ Não encontrado'}`);
    });

    // Teste 3: Verificar localStorage
    console.log('\n3. Verificando localStorage:');
    const storageKeys = ['theme', 'preset', 'layout', 'contrast', 'caption', 'direction', 'container'];
    storageKeys.forEach(key => {
        const value = localStorage.getItem(key);
        console.log(`   ${key}: ${value || 'não definido'}`);
    });

    // Teste 4: Testar uma função
    console.log('\n4. Testando mudança de tema:');
    try {
        layout_change('dark');
        setTimeout(() => {
            layout_change('light');
            console.log('   ✅ Mudança de tema funcionando');
        }, 1000);
    } catch (error) {
        console.log('   ❌ Erro na mudança de tema:', error);
    }

    console.log('\n=== FIM DO TESTE ===');
};

/**
 * Função para verificar problemas comuns
 */
window.checkLayoutIssues = function() {
    console.log('=== DIAGNÓSTICO DE PROBLEMAS ===');

    const issues = [];

    // Verificar se jQuery está carregado
    if (typeof $ === 'undefined') {
        issues.push('jQuery não está carregado');
    }

    // Verificar se os elementos do offcanvas existem
    const offcanvas = document.querySelector('#offcanvas_pc_layout');
    if (!offcanvas) {
        issues.push('Offcanvas do layout não encontrado');
    }

    // Verificar se há erros de CSS
    const themeButtons = document.querySelectorAll('.theme-layout .btn');
    if (themeButtons.length === 0) {
        issues.push('Botões de tema não encontrados');
    }

    // Verificar se há conflitos de event listeners
    const presetButtons = document.querySelectorAll('.preset-color a');
    presetButtons.forEach((btn, index) => {
        const listeners = btn.cloneNode(true);
        if (listeners.onclick) {
            issues.push(`Botão preset ${index} tem onclick inline conflitante`);
        }
    });

    if (issues.length === 0) {
        console.log('✅ Nenhum problema detectado!');
    } else {
        console.log('❌ Problemas encontrados:');
        issues.forEach(issue => console.log(`   - ${issue}`));
    }

    console.log('=== FIM DO DIAGNÓSTICO ===');
};

// Executar teste automático após carregamento
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        console.log('Layout Settings carregado. Execute testLayoutSettings() para diagnóstico completo.');
    }, 1000);
});

// Verificação adicional para garantir carregamento das configurações
window.addEventListener('load', function() {
    setTimeout(() => {
        console.log('🔄 Verificação adicional de configurações...');

        // Recarregar configurações se necessário
        if (typeof loadSavedSettings === 'function') {
            loadSavedSettings();
        }

        // Atualizar botões visuais
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            updateThemeModeButtons(savedTheme);
        }

        const savedPreset = localStorage.getItem('preset');
        if (savedPreset) {
            updatePresetButtons(savedPreset);
        }

        const savedLayout = localStorage.getItem('layout');
        if (savedLayout) {
            updateLayoutButtons(savedLayout);
        }

    }, 1000);
});
