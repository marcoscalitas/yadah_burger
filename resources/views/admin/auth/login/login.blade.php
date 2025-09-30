@extends('admin.auth.layouts.main')

@section('title', 'Login')

@section('content')
    <form method="POST" action="{{ route('admin.login.attempt') }}" id="loginForm" novalidate>
        {{-- Title --}}
        <h3 class="mb-2"><b>Faça login com o seu email</b></h3>

        @if (session()->has('success'))
            <div class="alert alert-success message-fade-out">
                <span>
                    <i class="fas fa-check-circle fa-lg me-2"></i>
                </span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Mostra erros globais --}}
        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-start">
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

        {{-- Proteção contra CSRF --}}
        @csrf

        <div class="mb-3">
            <label class="col-span-12 lg:col-span-4 col-form-label lg:text-right">
                Email <span class="text-danger">*</span>
            </label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" placeholder="email@exemplo.com" />
            <div class="text-danger d-flex align-items-center mt-1 error-none">
                <i class="fas fa-exclamation-circle me-1"></i> <span id="email-error"></span>
            </div>
        </div>

        <div class="mb-4">
            <label class="col-span-12 lg:col-span-4 col-form-label lg:text-right">
                Senha <span class="text-danger">*</span>
            </label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Senha" />
            <div class="text-danger d-flex align-items-center mt-1 error-none">
                <i class="fas fa-exclamation-circle me-1"></i> <span id="password-error"></span>
            </div>
        </div>

        <div class="flex mt-1 justify-between items-center flex-wrap">
            <div class="form-check">
                <input class="form-check-input input-primary" type="checkbox" name="remember" id="customCheckc1" />
                <label class="form-check-label text-muted" for="customCheckc1"> Lembre de mim?</label>
            </div>
            <h6 class="font-normal text-primary-500 mb-0">
                <a href="{{ route('admin.password.request') }}"> Esqueceu a sua senha? </a>
            </h6>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary w-full">Login</button>
        </div>

        <div class="flex justify-between items-end flex-wrap mt-4">
            <h6 class="f-w-500 mb-0">Não tem uma conta?</h6>
            <a href="register-v1.html" class="text-primary-500">Criar uma conta</a>
        </div>
    </form>
@endsection
