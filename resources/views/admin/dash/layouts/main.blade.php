<!doctype html>
<html lang="en" class="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
    dir="ltr" data-pc-theme_contrast="" data-pc-theme="light">
<!-- [Head] start -->

<head>
    <title>@yield('title', 'Dashboard') | Yadah Burguer</title>
    <!-- [Meta] -->
    @include('admin.includes.global-meta-tags')

    <!-- [ Global assets ] estar -->
    @include('admin.includes.global-assets')
    <!-- [Page specific CSS] -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/plugins/datepicker-bs5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/plugins/style.css') }}" />
    <!-- CSS DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/plugins/dataTables.bootstrap5.min.css') }}">
    <!-- Custom css -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom/main.css') }}" />
    @if (View::hasSection('custom-style'))
        @yield('custom-style')
    @endif
</head>
<!-- [Head] end -->

<!-- [Body] Start -->

<body>
    <!-- [ Loader ] estar -->
    @include('admin.includes.global-loader')
    <!-- [ Loader ] end -->

    <!-- [ Header ] start -->
    @include('admin.dash.components.header')
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ Email verification reminder ] start -->
            @if (session('email_verified') === false && is_null(getCurrentUser('admin')->email_verified_at))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    O seu e-mail ainda não foi verificado. Verifique sua caixa de entrada.
                </div>
            @endif
            <!-- [ Email verification reminder ] end -->

            <!-- [ Breadcrumb automático ] start -->
            @yield('breadcrumb')
            <!-- [ Breadcrumb automático ] end -->

            <!-- [ Messages ] start -->
            @include('admin.includes.global-request-msg')
            <!-- [ Messages ] end -->

            <!-- [ Content ] start -->
            @yield('content')
            <!-- [ Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- [ Footer ] end -->
    @include('admin.dash.components.footer')
    <!-- [ Footer ] end -->

    <!-- [ Required Js ] start -->
    @include('admin.includes.global-required-js')
    <!-- Custom js -->
    @if (View::hasSection('custom-scripts'))
        @yield('custom-scripts')
    @endif
    <!-- [ Required Js ] end -->

    <!-- [ Layout settings ] estartnd -->
    @include('admin.includes.global-layout-settings')
    <!-- [ Layout settings ] end -->
</body>
<!-- [Body] end -->

</html>
