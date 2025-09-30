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
    <!-- [ Global assets ] end -->

    <!-- Custom css -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom/main.css') }}" />
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
    <!-- [ Loader ] estart -->
    @include('admin.includes.global-loader')
    <!-- [ Loader ] end -->

    <!-- [ Main Content ] start -->
   @yield('content')
    <!-- [ Main Content ] end -->

    <!-- [ Required Js ] start -->
    @include('admin.includes.global-required-js')
    <!-- Custom js -->
    @if (View::hasSection('custom-scripts'))
        @yield('custom-scripts')
    @endif
    <!-- [ Required Js ] end -->

    <!-- [ Layout settings ] start -->
    @include('admin.includes.global-layout-settings')
    <!-- [ Layout settings ] end -->
</body>
<!-- [Body] end -->

</html>
