@extends('admin.auth.layouts.main')

@section('title', 'Esqueceu sua palavra-passe')

@section('content')
    <div class="mb-4">
        <h3 class="mt-2 mb-2"><b>Olá, verifique seu e-mail</b></h3>
        <p class="text-muted">Enviamos instruções de recuperação de senha para seu e-mail.</p>
    </div>

    <p class="mt-4 text-sm text-muted">Não se esqueça de verificar a caixa de SPAM.</p>

    <div class="grid mt-3">
        <a href="{{ route('admin.index') }}" class="btn btn-primary">Entrar</a>
    </div>
@endsection
