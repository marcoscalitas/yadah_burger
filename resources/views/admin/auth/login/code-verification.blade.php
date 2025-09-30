@extends('admin.auth.layouts.main')

@section('title', 'Esqueceu sua palavra-passe')

@section('content')
    <div class="mb-4">
        <h3 class="mb-2"><b>Digite o código de verificação</b></h3>
        <p class="text-muted mb-2">Código de verificação com 6 dígitos</p>
        <p class="">Enviamos o código para: jone. ****@company.com</p>
    </div>
    <form class="flex justify-between gap-2 text-center">
        <div class="col-span-3">
            <input type="number" class="form-control text-center code-input" placeholder="0" />
        </div>
        <div class="col-span-3">
            <input type="number" class="form-control text-center code-input" placeholder="0" />
        </div>
        <div class="col-span-3">
            <input type="number" class="form-control text-center code-input" placeholder="0" />
        </div>
        <div class="col-span-3">
            <input type="number" class="form-control text-center code-input" placeholder="0" />
        </div>
        <div class="col-span-3">
            <input type="number" class="form-control text-center code-input" placeholder="0" />
        </div>
        <div class="col-span-3">
            <input type="number" class="form-control text-center code-input" placeholder="0" />
        </div>
    </form>
    <div class="grid mt-4">
        <button type="button" class="btn btn-primary">Continuar</button>
    </div>
    <div class="flex justify-start items-end mt-5">
        <p class="mb-0">Não recebeu o e-mail?</p>
        <a href="code-verification-v1.html#" class="text-primary-500 ml-2">Renviar código</a>
    </div>
@endsection
