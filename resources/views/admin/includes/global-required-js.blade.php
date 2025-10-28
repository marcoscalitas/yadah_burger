<!-- jQuery (precisas antes de DataTables!) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script data-cfasync="false" src="{{ asset('admin/assets/js/email-decode.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/i18next.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/i18nextHttpBackend.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/icon/custom-font.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/component.js') }}"></script>
<script src="{{ asset('admin/assets/js/theme.js') }}"></script>
<script src="{{ asset('admin/assets/js/multi-lang.js') }}"></script>
<script src="{{ asset('admin/assets/js/script.js') }}"></script>
<script defer src="https://fomo.codedthemes.com/pixel/CDkpF1sQ8Tt5wpMZgqRvKpQiUhpWE3bc"></script>

{{-- <div class="floting-button fixed bottom-[50px] right-[30px] z-[1030]">
    <a href="https://1.envato.market/zNkqj6"
        class="btn btn-danger buynowlinks animate-[btn-floating_2s_infinite] max-sm:p-[13px] max-sm:rounded-full inline-flex items-center gap-2"
        data-pc-toggle="tooltip" data-pc-title="Buy Now">
        <i class="ph-duotone ph-shopping-cart text-lg leading-none"></i>
        <span class="hidden sm:inline-block">Buy Now</span>
    </a>
</div> --}}

<script>
    // Aguarda que as funções sejam carregadas antes de executar
    document.addEventListener('DOMContentLoaded', function() {
        // Aguarda um pouco para garantir que o layout-settings.js já executou
        setTimeout(function() {
            // Só aplica configurações padrão se não houver configurações salvas
            if (!localStorage.getItem('theme') && typeof layout_change === 'function') {
                layout_change('light');
            }
            if (!localStorage.getItem('contrast') && typeof layout_theme_contrast_change ===
                'function') {
                layout_theme_contrast_change('false');
            }
            if (!localStorage.getItem('container') && typeof change_box_container === 'function') {
                change_box_container('false');
            }
            if (!localStorage.getItem('caption') && typeof layout_caption_change === 'function') {
                layout_caption_change('true');
            }
            if (!localStorage.getItem('direction') && typeof layout_rtl_change === 'function') {
                layout_rtl_change('false');
            }
            if (!localStorage.getItem('preset') && typeof preset_change === 'function') {
                preset_change('preset-1');
            }
            if (!localStorage.getItem('layout') && typeof main_layout_change === 'function') {
                main_layout_change('vertical');
            }
        }, 500); // Aguarda 500ms para garantir ordem de execução
    });
</script>

<!-- [Page Specific JS] start -->
<!-- bootstrap-datepicker -->
<script src="{{ asset('admin/assets/js/plugins/datepicker-full.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/peity-vanilla.min.js') }}"></script>
<!-- custom widgets js -->
<script src="{{ asset('admin/assets/js/widgets/revenue-sales-chart.js') }}"></script>
<script src="{{ asset('admin/assets/js/widgets/invites-goal-chart.js') }}"></script>
<script src="{{ asset('admin/assets/js/widgets/course-report-bar-chart.js') }}"></script>
<script src="{{ asset('admin/assets/js/widgets/total-revenue-line-1-chart.js') }}"></script>
<script src="{{ asset('admin/assets/js/widgets/total-revenue-line-2-chart.js') }}"></script>
<script src="{{ asset('admin/assets/js/widgets/student-states-chart.js') }}"></script>
<script src="{{ asset('admin/assets/js/widgets/activity-line-chart.js') }}"></script>
<script src="{{ asset('admin/assets/js/widgets/widget-calender.js') }}"></script>
<script src="{{ asset('admin/assets/js/widgets/visitors-bar-chart.js') }}"></script>
<script src="{{ asset('admin/assets/js/widgets/earning-courses-line-chart.js') }}"></script>
<script src="{{ asset('admin/assets/js/widgets/table-donut.js') }}"></script>

<!-- Core DataTables -->
<script src="{{ asset('admin/assets/js/plugins/dataTables.min.js') }}"></script>
<!-- Extensões (se precisares) -->
<script src="{{ asset('admin/assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/dataTables.buttons.min.js') }}"></script>
{{-- Data table settings --}}
{{-- Custom js --}}
<script src="{{ asset('admin/assets/js/custom/utilities.js') }}"></script>
<script src="{{ asset('admin/assets/js/custom/layout-settings.js') }}"></script>

<script>
    $(document).ready(function() {
        // Função genérica para aplicar fade out em alerts
        function applyFadeOut(selector, timeout) {
            $(selector).each(function() {
                var $alert = $(this);

                setTimeout(function() {
                    $alert.fadeOut('slow');
                }, timeout);
            });
        }

        // Apply fade out for alerts
        applyFadeOut('.message-fade-out', 6000);
        applyFadeOut('.message-fade-out-err', 16000);

        // Data table
        var $table = $('#pc-dt-simple');

        // Verifica se a tabela tem dados reais (ignora linha de "empty")
        var hasData = $table.find('tbody tr').length > 0 &&
            !$table.find('tbody tr td[colspan]').length;

        if (hasData) {
            $table.DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                responsive: true,
                pagingType: "full_numbers",
                order: [
                    [0, 'asc']
                ],
                destroy: true, // Permite reinicializar a tabela
                autoWidth: false, // Desabilita o cálculo automático de largura
                scrollX: true, // Permite scroll horizontal se necessário
                columnDefs: [{
                    targets: '_all',
                    defaultContent: '', // Conteúdo padrão para colunas vazias
                    width: null // Remove larguras predefinidas
                }],
                language: {
                    decimal: ",",
                    thousands: ".",
                    emptyTable: "Nenhum dado disponível na tabela",
                    info: "Mostrando _START_ até _END_ de _TOTAL_ registos",
                    infoEmpty: "Mostrando 0 até 0 de 0 registos",
                    infoFiltered: "(filtrado de _MAX_ registos no total)",
                    lengthMenu: "Mostrar _MENU_ registos",
                    loadingRecords: "A carregar...",
                    processing: "A processar...",
                    search: "Pesquisar:",
                    zeroRecords: "Não foram encontrados resultados",
                    paginate: {
                        first: "Primeiro",
                        last: "Último",
                        next: ">",
                        previous: "<"
                    },
                    aria: {
                        sortAscending: ": ativar para ordenar a coluna de forma ascendente",
                        sortDescending: ": ativar para ordenar a coluna de forma descendente"
                    }
                }
            });
        }
    });
</script>
