@extends('admin.dash.layouts.main')

@section('title', 'Alterar Email')

@section('custom-style')
    <!-- [css ] -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/plugins/animate.min.css') }}" />
@endsection

@section('content')
    <!-- [ breadcrumb ] start -->
    <!-- [ breadcrumb ] start -->
    @include('admin.dash.components.breadcrumb', [
        'title' => 'Alterar E-mail ',
        'items' => [
            ['label' => 'Definições', 'url' => route('admin.settings.index')],
            ['label' => 'Alterar E-mail', 'url' => route('admin.settings.change-email')],
        ],
    ])
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-6">
        <!-- [ sample-page ] start -->
        <div class="col-span-12">
            @if (session()->has('success'))
                <div class="alert alert-success message-fade-out">
                    <span>
                        <i class="fas fa-check-circle fa-lg me-2"></i>
                    </span>
                    {{ session('success') }}
                </div>
            @endif

            <div class="tab-pane">
                <form action="{{ route('admin.settings.change-email') }}" method="POST">
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
                                    <div class="mb-4">
                                        <label class="form-label">Senha atual</label>
                                        <input type="password" name="current_password" class="form-control"
                                            placeholder="Digite sua senha atual">
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
