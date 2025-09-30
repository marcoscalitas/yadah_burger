@extends('admin.auth.layouts.main')

@section('title', 'Esqueceu sua palavra-passe')

@section('content')
    <div class="flex justify-between items-end mt-2 mb-4">
        <h3 class="mb-0"><b>Esqueceu sua senha</b></h3>
        <a href="{{ route('admin.login') }}" class="text-primary-500">Voltar ao login</a>
    </div>
    <div class="mb-3">
        <label class="form-label">Endereço de email</label>
        <input type="email" class="form-control" id="floatingInput" placeholder="exemplo@gmail.com" />
    </div>
    <p class="mt-4 text-sm text-muted">Não se esqueça de verificar a caixa de SPAM.</p>
    <div class="grid mt-3">
        <button type="button" class="btn btn-primary">Enviar e-mail de redifinição de senha</button>
    </div>
@endsection
