@extends('admin.auth.layouts.main')

@section('title', 'Esqueceu sua palavra-passe')

@section('content')
    <form method="POST" action="{{ route('admin.password.update') }}">
        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-start mt-4">
                <div>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-4">
            <h3 class="mb-2"><b>Redefinir palavra-passe</b></h3>
            <p class="text-muted">Por favor, crie uma nova palavra-passe</p>
        </div>

        <div class="mb-3">
            <label class="form-label">Palavra-passe</label>
            <input type="password" class="form-control" name="password" placeholder="Palavra-passe" />
        </div>

        <div class="mb-3">
            <label class="form-label">Confirmar palavra-passe</label>
            <input type="password" class="form-control" name="password_confirmation"
                placeholder="Confirmar palavra-passe" />
        </div>

        <div class="grid mt-4">
            <button type="submit" class="btn btn-primary">Redefinir palavra-passe</button>
        </div>
    </form>
@endsection
