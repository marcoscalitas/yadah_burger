@extends('admin.dash.layouts.main')

@section('title', 'Alterar senha')

@section('content')
    <!-- [ breadcrumb ] start -->
    @include('admin.dash.components.breadcrumb', [
        'title' => 'Alterar senha ',
        'items' => [
            ['label' => 'Definições', 'url' => route('admin.settings.index')],
            ['label' => 'Alterar senha', 'url' => route('admin.settings.change-password')],
        ],
    ])
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-6">
        <!-- [ sample-page ] start -->
        <div class="col-span-12">
            <div class="tab-content">
                @if (session()->has('success'))
                    <div class="alert alert-success message-fade-out">
                        <span>
                            <i class="fas fa-check-circle fa-lg me-2"></i>
                        </span>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="tab-pane">
                    <form action="{{ route('admin.settings.update-password') }}" method="POST">
                        @csrf

                        <div class="card">
                            <div class="card-header">
                                <h5>Alterar senha</h5>
                            </div>
                            <div class="card-body">
                                <div class="grid grid-cols-12 gap-6">
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label">Senha antiga</label>
                                            <input type="password" name="current_password" class="form-control" />
                                            @error('current_password')
                                                <div class="text-danger d-flex align-items-center mt-1">
                                                    <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Nova senha</label>
                                            <input type="password" name="password" id="password" class="form-control" />
                                            @error('password')
                                                <div class="text-danger d-flex align-items-center mt-1">
                                                    <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Confirmar senha</label>
                                            <input type="password" name="password_confirmation" class="form-control" />
                                            @error('password_confirmation')
                                                <div class="text-danger d-flex align-items-center mt-1">
                                                    <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-span-12 sm:col-span-6">
                                        <h5>A nova senha deve conter:</h5>
                                        <ul
                                            class="password-rules rounded-lg *:py-4 *:px-0 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                                            <li class="rule min-char list-group-item">
                                                <i class="ti ti-circle-check text-success f-16 me-2"></i>
                                                Pelo menos 8 caracteres
                                            </li>
                                            <li class="rule lower list-group-item">
                                                <i class="ti ti-circle-check text-success f-16 me-2"></i>
                                                Pelo menos 1 letra minúscula (a-z)
                                            </li>
                                            <li class="rule upper list-group-item">
                                                <i class="ti ti-circle-check text-success f-16 me-2"></i>
                                                Pelo menos 1 letra maiúscula (A-Z)
                                            </li>
                                            <li class="rule number list-group-item">
                                                <i class="ti ti-circle-check text-success f-16 me-2"></i>
                                                Pelo menos 1 número (0-9)
                                            </li>
                                            <li class="rule special list-group-item">
                                                <i class="ti ti-circle-check text-success f-16 me-2"></i>
                                                Pelo menos 1 caractere especial
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right btn-page mt-4">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mx-1">Cancelar</a>
                                <button type="submit" class="btn btn-primary mx-1" id="submitBtn">Atualizar
                                    senha</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('custom-scripts')
    {{-- Calls --}}
    <script>
        $(document).ready(function() {
            validatePassword();
        });
    </script>
@endsection
