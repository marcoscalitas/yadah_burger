@extends('admin.auth.layouts.main')

@section('title', 'Verificação de E-mail')

@section('content')
    <div class="mb-4">
        <h3 class="mt-2 mb-2"><b>Olá, verifique seu e-mail</b></h3>
        <p class="text-muted">Enviamos um link de verificação para seu e-mail. Clique no link para ativar sua conta.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            Um novo link de verificação foi enviado para o seu endereço de email!
        </div>
    @endif

    @if (session('message'))
        <div class="alert alert-info mb-3" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            {{ session('message') }}
        </div>
    @endif

    <p class="mt-4 text-sm text-muted">Não se esqueça de verificar a caixa de SPAM.</p>

    <div class="grid mt-3 gap-2">
        <form method="POST" action="{{ route('admin.verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-paper-plane me-2"></i>
                Reenviar E-mail
            </button>
        </form>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100">
                <i class="fas fa-sign-out-alt me-2"></i>
                Sair
            </button>
        </form>
    </div>
@endsection
