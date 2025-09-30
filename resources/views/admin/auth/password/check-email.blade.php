@extends('admin.auth.layouts.main')

@section('title', 'Esqueceu sua palavra-passe')

@section('content')
    <div class="mb-4">
        <h3 class="mt-2 mb-2"><b>Olá, verifique seu e-mail</b></h3>
        <p class="text-muted">Enviamos instruções de recuperação de senha para seu e-mail.</p>
    </div>
    <div class="grid mt-3">
        <button type="button" class="btn btn-primary">Entrar</button>
    </div>
    <div class="relative my-5">
        <div aria-hidden="true" class="absolute flex inset-0 items-center">
            <div class="w-full border-t border-theme-border dark:border-themedark-border"></div>
        </div>
        <div class="relative flex justify-center">
            <span class="px-4 bg-theme-cardbg dark:bg-themedark-cardbg">Inscreva-se com</span>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-3">
        <div class="col-span-4">
            <div class="grid">
                <button type="button"
                    class="btn mt-2 flex items-center justify-center gap-2 text-theme-bodycolor dark:text-themedark-bodycolor bg-theme-bodybg dark:bg-themedark-bodybg border border-theme-border dark:border-themedark-border hover:border-primary-500 dark:hover:border-primary-500">
                    <img src="{{ asset('admin/assets/images/authentication/facebook.svg') }}" alt="img" />
                    <span class="d-none d-sm-inline-block"> Google</span>
                </button>
            </div>
        </div>
        <div class="col-span-4">
            <div class="grid">
                <button type="button"
                    class="btn mt-2 flex items-center justify-center gap-2 text-theme-bodycolor dark:text-themedark-bodycolor bg-theme-bodybg dark:bg-themedark-bodybg border border-theme-border dark:border-themedark-border hover:border-primary-500 dark:hover:border-primary-500">
                    <img src="{{ asset('admin/assets/images/authentication/twitter.svg') }}" alt="img" />
                    <span class="d-none d-sm-inline-block"> Twitter</span>
                </button>
            </div>
        </div>
        <div class="col-span-4">
            <div class="grid">
                <button type="button"
                    class="btn mt-2 flex items-center justify-center gap-2 text-theme-bodycolor dark:text-themedark-bodycolor bg-theme-bodybg dark:bg-themedark-bodybg border border-theme-border dark:border-themedark-border hover:border-primary-500 dark:hover:border-primary-500">
                    <img src="{{ asset('admin/assets/images/authentication/google.svg') }}" alt="img" />
                    <span class="d-none d-sm-inline-block"> Facebook</span>
                </button>
            </div>
        </div>
    </div>
@endsection
