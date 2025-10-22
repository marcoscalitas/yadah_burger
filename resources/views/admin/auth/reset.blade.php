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
        <div class="mb-4 password-wrapper">
            <label class="col-span-12 lg:col-span-4 col-form-label lg:text-right">
                Senha <span class="text-danger">*</span>
            </label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="Senha" />
            <a href="#" class="password-toggle password-toggle-auth">
                <i class="ti ti-eye text-xl leading-none"></i>
            </a>
            @error('password')
                <div class="text-danger d-flex align-items-center mt-1">
                    <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-4 password-wrapper">
            <label class="col-span-12 lg:col-span-4 col-form-label lg:text-right">
                Confirmar Senha <span class="text-danger">*</span>
            </label>
            <input type="password" name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Senha" />
            <a href="#" class="password-toggle password-toggle-auth">
                <i class="ti ti-eye text-xl leading-none"></i>
            </a>
            @error('password_confirmation')
                <div class="text-danger d-flex align-items-center mt-1">
                    <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>
        <div class="grid mt-4">
            <button type="submit" class="btn btn-primary">Redefinir senha</button>
        </div>
    </form>
@endsection


@section('custom-scripts')
    {{-- Calls --}}
    <script>
        $(document).ready(function() {
            togglePasswordVisibility();
        });
    </script>
@endsection
