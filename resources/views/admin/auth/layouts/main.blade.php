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
    <div class="auth-main relative">
        <div class="auth-wrapper v1 flex items-center w-full h-full min-h-screen">
            <div
                class="auth-form flex items-center justify-center grow flex-col min-h-screen bg-cover relative p-6 bg-[url('../images/authentication/img-auth-bg.jpg')] dark:bg-none dark:bg-themedark-bodybg">
                <div class="card sm:my-12 w-full max-w-[480px] shadow-none">
                    <div class="card-body !p-10">
                        <div class="flex items-center justify-center center align-content-center gap-1 mb-2">
                            <a href="{{ route('admin.login') }}">
                                <img class="mx-auto shrink-0 w-[45px] h-[45px] rounded-full"
                                    src="{{ asset('admin/assets/images/yadah_burguer_logo.jpeg') }}" alt="Logo" />
                            </a>
                            <h4 class="text-center">Yadah Burguer</h4>
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
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
