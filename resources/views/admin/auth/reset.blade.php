@extends('admin.auth.layouts.main')

@section('title', 'Esqueceu sua senha')

@section('content')
    <form method="POST" action="{{ route('admin.password.update') }}">
        <!-- [ Messages ] start -->
        @include('admin.includes.global-request-msg')
        <!-- [ Messages ] end -->

        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-4">
            <h3 class="mb-2"><b>Redefinir senha</b></h3>
            <p class="text-muted">Por favor, crie uma nova senha</p>
        </div>

        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" class="form-control" name="password" placeholder="Senha" />
        </div>

        <div class="mb-3">
            <label class="form-label">Confirmar senha</label>
            <input type="password" class="form-control" name="password_confirmation"
                placeholder="Confirmar senha" />
        </div>

        <div class="grid mt-4">
            <button type="submit" class="btn btn-primary">Redefinir senha</button>
        </div>
    </form>
@endsection
