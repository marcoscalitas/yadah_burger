@extends('admin.auth.layouts.main')

@section('title', 'Esqueceu sua palavra-passe')

@section('content')
    <div class="mb-4">
        <h3 class="mb-2"><b>Redefinir palavra-passe</b></h3>
        <p class="text-muted">Por favor, crie uma nova palavra-passe</p>
    </div>
    <div class="mb-3">
        <label class="form-label">Palavre-passe</label>
        <input type="password" class="form-control" id="floatingInput" placeholder="Palavre-passe" />
    </div>
    <div class="mb-3">
        <label class="form-label">Confirmar palavra-passe</label>
        <input type="password" class="form-control" id="floatingInput1" placeholder="Confirmar palavra-passe" />
    </div>
    <div class="grid mt-4">
        <button type="button" class="btn btn-primary">Redefinir palavra-passe</button>
    </div>
@endsection
