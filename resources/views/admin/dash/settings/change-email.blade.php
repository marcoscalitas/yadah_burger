@extends('admin.dash.layouts.main')

@section('title', 'Alterar E-mail')

@section('custom-style')
    <!-- [css ] -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/plugins/animate.min.css') }}" />
@endsection

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.settings.change.email'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-6">
        <!-- [ sample-page ] start -->
        <div class="col-span-12">
            <div class="tab-pane">
                <form action="{{ route('admin.settings.change.email') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h5>Alterar e-mail</h5>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 md:col-span-8">
                                    <!-- Novo Email -->
                                    <div class="mb-4">
                                        <label class="form-label">Novo e-mail</label>
                                        <input type="email" name="new_email" class="form-control" value=""
                                            placeholder="Digite o novo email">
                                        @error('new_email')
                                            <div class="text-danger d-flex align-items-center mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Senha Atual -->
                                    <div class="mb-4 password-wrapper">
                                        <label class="form-label">Senha atual</label>
                                        <input type="password" name="current_password" class="form-control"
                                            placeholder="Digite sua senha atual">
                                        <a href="#" class="password-toggle">
                                            <i class="ti ti-eye text-xl leading-none"></i>
                                        </a>
                                        @error('current_password')
                                            <div class="text-danger d-flex align-items-center mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right btn-page mt-4">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mx-1">Cancelar</a>
                            <button type="submit" class="btn btn-primary mx-1" id="submitBtn">Atualizar
                                e-mail
                            </button>
                        </div>
                    </div>
                </form>
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
            togglePasswordVisibility();
        });
    </script>
@endsection
