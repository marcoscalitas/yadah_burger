@extends('admin.dash.layouts.main')

@section('title', 'Editar Utilizador')

@section('breadcrumb')
    @include(
        'admin.dash.components.breadcrumb',
        getBreadcrumb(
            'admin.users.edit',
            [['label' => 'Editar Utilizador', 'url' => route('admin.users.edit', $user->id)]],
            'Editar utilizador'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h5>Editar Utilizador</h5>
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
                                        name="fullname" value="{{ old('fullname', $user->fullname) }}" />
                                    <small class="form-text text-muted">
                                        Utilizador: {{ $user->getShortName() }}
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
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $user->email) }}" />
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
                                            name="phone" value="{{ old('phone', $user->getFormattedPhone(false)) }}"
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
                                    <select class="form-select @error('gender') is-invalid @enderror" name="gender">
                                        <option value="M" {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>
                                            Masculino</option>
                                        <option value="F" {{ old('gender', $user->gender) == 'F' ? 'selected' : '' }}>
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
                                        name="birthdate" value="{{ old('birthdate', $user->birthdate) }}" />
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
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                            Administrador</option>
                                        <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>
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

                                                        <!-- Criado por -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Criado por
                                    </label>
                                    <input type="text" name="created_by_display" class="form-control disabled-field" disabled
                                        value="{{ $user->createdBy ? $user->createdBy->fullname : 'Sistema' }}" />
                                </div>
                            </div>

                            <!-- Editado por -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Editado por
                                    </label>
                                    <input type="text" name="updated_by_display" class="form-control disabled-field" disabled
                                        value="{{ $user->updatedBy ? $user->updatedBy->fullname : 'Sistema' }}" />
                                </div>
                            </div>

                            <!-- Data de Criação -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Data de Criação
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="created_at" class="form-control disabled-field" disabled
                                        value="{{ old('role', $user->created_at->format('Y-m-d')) }}" />
                                </div>
                            </div>

                            <!-- Data de Edição -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Data de Edição
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="updated_at" class="form-control disabled-field" disabled
                                        value="{{ old('role', $user->updated_at->format('Y-m-d')) }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 text-right">
                    <button type="submit" class="btn btn-primary">Salvar alterações</button>
                </div>
            </form>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
