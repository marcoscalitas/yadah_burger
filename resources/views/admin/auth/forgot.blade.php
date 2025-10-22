@extends('admin.auth.layouts.main')

@section('title', 'Esqueceu sua senha')

@section('content')
    <form method="POST" action="{{ route('admin.password.email') }}">
        <!-- [ Messages ] start -->
        @include('admin.includes.global-request-msg')
        <!-- [ Messages ] end -->

        @csrf

        <div class="flex justify-between items-end mt-2 mb-4">
            <h3 class="mb-0"><b>Esqueceu sua senha</b></h3>
            <a href="{{ route('admin.login') }}" class="text-primary-500">Voltar ao login</a>
        </div>

        <div class="mb-3">
            <label class="form-label">Endereço de email</label>
            <input type="email" name="email" class="form-control" placeholder="exemplo@gmail.com" />

        </div>

        <p class="mt-4 text-sm text-muted">Não se esqueça de verificar a caixa de SPAM.</p>

        <div class="grid mt-3">
            <button type="submit" class="btn btn-primary">
                Enviar e-mail de redefinição de senha
            </button>
        </div>
    </form>
@endsection
