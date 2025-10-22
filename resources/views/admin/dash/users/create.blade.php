@extends('admin.dash.layouts.main')

@section('title', 'Adicionar Utilizador')

@section('custom-style')
    <!-- [css ] -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/plugins/uppy.min.css') }}" />
@endsection

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.users.create'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h5>Adicionar Utilizador</h5>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-12 gap-6">

                            <!-- Nome Completo -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Nome Completo
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                        name="fullname" value="{{ old('fullname') }}" />
                                    @error('fullname')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Email
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" />
                                    @error('email')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Telefone -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Telefone
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">+244</span>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            name="phone" value="{{ old('phone') }}" maxlength="11" id="phone-number" />
                                    </div>
                                    @error('phone')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sexo -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">Sexo</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" name="gender">
                                        <option value="">Selecione</option>
                                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>
                                            Masculino</option>
                                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>
                                            Feminino</option>
                                    </select>
                                    @error('gender')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Data de Nascimento -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Data de Nascimento
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                                        name="birthdate" value="{{ old('birthdate') }}" />
                                    @error('birthdate')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Função -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">Função</label>
                                    <select class="form-select @error('role') is-invalid @enderror" name="role">
                                        <option value="">Selecione</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                            Administrador</option>
                                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>
                                            Funcionário</option>
                                    </select>
                                    @error('role')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Senha -->
                            <div class="col-span-12 sm:col-span-6 password-wrapper">
                                <label class="form-label">Senha <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password">
                                <a href="#" class="password-toggle">
                                    <i class="ti ti-eye text-xl leading-none"></i>
                                </a>
                                @error('password')
                                    <div class="text-danger d-flex align-items-center mt-1">
                                        <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Confirmação de Senha -->
                            <div class="col-span-12 sm:col-span-6 password-wrapper">
                                <label class="form-label">Confirmar Senha <span class="text-danger">*</span></label>
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    name="password_confirmation">
                                <a href="#" class="password-toggle">
                                    <i class="ti ti-eye text-xl leading-none"></i>
                                </a>
                                @error('password_confirmation')
                                    <div class="text-danger d-flex align-items-center mt-1">
                                        <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- User photo -->
                            <div class="col-span-12 sm:col-span-12">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Escolher foto
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                        name="photo" accept="image/*" />
                                    @error('photo')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 text-right">
                    <button type="submit" class="btn btn-primary">Adicionar Utilizador</button>
                </div>
            </form>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function() {
            formatPhoneNumber('phone-number');
            togglePasswordVisibility();
        });
    </script>
@endsection
