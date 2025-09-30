@extends('admin.dash.layouts.main')

@section('title', 'Editar Perfil')

@section('content')
    <!-- [ breadcrumb ] start -->
    @include('admin.dash.components.breadcrumb', [
        'title' => 'Editar Perfil',
        'items' => [
            ['label' => 'Meu Perfil', 'url' => route('admin.profile.index')],
            ['label' => 'Editar Perfil', 'url' => route('admin.profile.edit')],
        ],
    ])
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-6">
        <!-- [ sample-page ] start -->
        <div class="col-span-12">
            <div class="tab-content">
                <div class="tab-pane" id="profile-2">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <form action="{{ route('admin.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Editar Perfil</h5>
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
                                                    <input type="text"
                                                        class="form-control @error('fullname') is-invalid @enderror"
                                                        name="fullname"
                                                        value="{{ old('fullname', auth('admin')->user()->fullname) }}" />
                                                    <small class="form-text text-muted">
                                                        Utilizador: {{ auth('admin')->user()->getShortName() }}
                                                    </small>
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
                                                    <input type="text"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email"
                                                        value="{{ old('email', auth('admin')->user()->email) }}" />
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
                                                        <input type="text"
                                                            class="form-control @error('phone') is-invalid @enderror"
                                                            name="phone"
                                                            value="{{ old('phone', auth('admin')->user()->getFormattedPhone(false)) }}"
                                                            id="phone-number" maxlength="11" />
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
                                                    <select class="form-select @error('gender') is-invalid @enderror"
                                                        name="gender">
                                                        <option value="M"
                                                            {{ old('gender', auth('admin')->user()->gender) == 'M' ? 'selected' : '' }}>
                                                            Masculino</option>
                                                        <option value="F"
                                                            {{ old('gender', auth('admin')->user()->gender) == 'F' ? 'selected' : '' }}>
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
                                                    <input type="date"
                                                        class="form-control @error('birthdate') is-invalid @enderror"
                                                        name="birthdate"
                                                        value="{{ old('birthdate', auth('admin')->user()->birthdate) }}" />
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
                                                    <select
                                                        class="form-select @if (auth('admin')->user()->role == 'staff') disabled-field @endif
                                                        @error('role') is-invalid @enderror"
                                                        name="role">

                                                        <option value="admin"
                                                            {{ old('role', auth('admin')->user()->role) == 'admin' ? 'selected' : '' }}>
                                                            Administrador</option>
                                                        <option value="staff"
                                                            {{ old('role', auth('admin')->user()->role) == 'staff' ? 'selected' : '' }}>
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

                                            <!-- Data de Criação -->
                                            <div class="col-span-12 sm:col-span-6">
                                                <div class="mb-1">
                                                    <label class="form-label">
                                                        Data de Criação
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="date" name="created_at"
                                                        class="form-control disabled-field" disabled
                                                        value="{{ old('role', auth('admin')->user()->getFormattedDate('created_at', 'Y-m-d')) }}" />
                                                </div>
                                            </div>

                                            <!-- Data de Edição -->
                                            <div class="col-span-12 sm:col-span-6">
                                                <div class="mb-1">
                                                    <label class="form-label">
                                                        Data de Edição
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="date" name="updated_at"
                                                        class="form-control disabled-field" disabled
                                                        value="{{ old('role', auth('admin')->user()->getFormattedDate('updated_at', 'Y-m-d')) }}" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-12 text-right">
                                    <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('custom-scripts')
    {{-- Calls --}}
    <script>
        $(document).ready(function() {
            formatPhoneInput('phone-number');
        });
    </script>
@endsection
