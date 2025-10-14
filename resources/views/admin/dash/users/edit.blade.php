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
        <div class="col-span-12 lg:col-span-4 2xl:col-span-3">
            <div class="card">
                <div class="card-body relative">
                    <div class="absolute right-0 top-0 p-[25px]">
                        <button type="button"
                            class="flex items-center justify-center w-9 h-9 rounded-full bg-primary-500 text-white shadow-md cursor-pointer transition hover:bg-primary-600 active:scale-95"
                            data-pc-toggle="modal" data-pc-target="#editPhotoModal" data-pc-animate="sticky-up">
                            <i class="ti ti-camera text-lg"></i>
                        </button>
                    </div>
                    <div class="text-center mt-3">
                        <div class="chat-avtar inline-flex mx-auto justify-center">
                            <img class="shrink-0 w-[90px] h-[90px] round-image" src="{{ $user->getImageUrl() }}"
                                alt="user-image" style="height: 100px; width: 100px;" />
                            <span
                                class="absolute status-indicator block w-4 h-4 bg-green-500
                                                border-2 border-white rounded-full"></span>
                        </div>
                        <h5 class="mb-0">{{ $user->getShortName() }}</h5>
                        <p class="text-muted text-sm">{{ $user->getRoleLabel() }}</p>
                        <hr class="my-4 border-secondary-500/10" />
                        <div
                            class="grid grid-cols-12 gap-0 divide-x rtl:divide-x-reverse divide-inherit divide-theme-border dark:divide-themedark-border">
                            <div class="col-span-4">
                                <h5 class="mb-0 text-xs">{{ $user->getAge() }}</h5>
                                <small class="text-muted">Idade</small>
                            </div>
                            <div class="col-span-4">
                                <h5 class="mb-0 text-xs">{{ $user->gender }}</h5>
                                <small class="text-muted">Sexo</small>
                            </div>
                            <div class="col-span-4">
                                {!! getStatusBadge($user->user_status) !!}
                                <small class="text-muted">Status</small>
                            </div>
                        </div>
                        <hr class="my-4 border-secondary-500/10" />
                        <div class="inline-flex items-center gap-3 w-full mb-3">
                            <i class="ti ti-mail"></i>
                            <p class="mb-0">{{ $user->email }}</p>
                        </div>
                        <div class="inline-flex items-center gap-3 w-full mb-3">
                            <i class="ti ti-phone"></i>
                            <p class="mb-0">{{ $user->getFormattedPhone() }}</p>
                        </div>
                        <div class="inline-flex items-center gap-3 w-full mb-3">
                            <i class="ti ti-map-pin"></i>
                            <p class="mb-0">Angola, Luanda</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Editar Foto -->
            <div id="editPhotoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editPhotoModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form id="photoUploadForm" class="form-with-csrf-token" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title font-semibold">Editar foto</h5>
                                <button type="button" data-pc-modal-dismiss="#editPhotoModal"
                                    class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <div class="alert text-center" id="photoMessage" style="display:none;">
                                    <span id="photoMessageContent"></span>
                                </div>
                                <!-- Preview fixo -->
                                <div class="preview-wrap mx-auto relative" style="width:135px; height:135px;">
                                    <div class="preview-container"
                                        style="width:135px;height:135px;border-radius:50%;overflow:hidden;position:relative;
                                    border:4px solid rgba(255,255,255,0.06);box-shadow:0 6px 18px rgba(0, 0, 0, 0.081);background:#f4f4f4;">
                                        <img id="profilePreview" src="{{ asset($user->getImageUrl()) }}" alt="Foto actual"
                                            style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;
                                        object-position:center center;display:block;max-width:none;">
                                    </div>
                                </div>

                                <!-- Texto Foto actual -->
                                <p class="mt-3 text-sm text-gray-400">Foto actual</p>

                                <!-- Aviso -->
                                <p class="mt-2 text-sm text-gray-500 max-w-[420px] mx-auto">
                                    Certifique-se de que sua imagem esteja no formato <b>PNG</b> ou
                                    <b>JPEG</b>,
                                    e que tenha no máximo <b>3MB</b>.
                                </p>
                            </div>
                            <div class="modal-footer flex justify-center gap-3 border-t">
                                <label for="profilePhotoInput" class="btn btn-outline-primary cursor-pointer px-4">
                                    Selecionar foto</label>
                                <input type="file" id="profilePhotoInput" name="photo" accept="image/png,image/jpeg"
                                    style="display:none;">
                                <button id="savePhotoBtn" type="button" class="btn btn-primary px-5">
                                    Salvar foto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
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
                                        <option value="M"
                                            {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>
                                            Masculino</option>
                                        <option value="F"
                                            {{ old('gender', $user->gender) == 'F' ? 'selected' : '' }}>
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
                                        <option value="admin"
                                            {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                            Administrador</option>
                                        <option value="staff"
                                            {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>
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
                                    <input type="text" name="created_by_display" class="form-control disabled-field"
                                        disabled
                                        value="{{ $user->createdBy ? $user->createdBy->fullname : 'Sistema' }}" />
                                </div>
                            </div>

                            <!-- Editado por -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Editado por
                                    </label>
                                    <input type="text" name="updated_by_display" class="form-control disabled-field"
                                        disabled
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

@section('custom-scripts')
    {{-- Calls --}}
    <script>
        $(document).ready(function() {
            // Preview
            setupImagePreview('#profilePhotoInput', '#profilePreview', 'photoMessage');
            uploadUserPhoto(
                @json(url("/admin/users/{$user->id}/update-photo")),
                @json(url('/admin/users'))
            );
        });
    </script>
@endsection
