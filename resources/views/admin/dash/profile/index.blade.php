@extends('admin.dash.layouts.main')

@section('title', 'Meu Perfil')

@section('custom-style')
    <!-- [css ] -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom/main.css') }}" />
@endsection

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.profile.index'))
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
                            <p class="mb-0">{{ getFormattedPhone($user->phone) }}</p>
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
                                        <img id="profilePreview" src="{{ $user->getImageUrl() }}" alt="Foto actual"
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
                            <div class="modal-footer flex justify-end gap-3 border-t">
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
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h5 class="mb-0">Dados pessoais</h5>
                    <div class="shrink-0">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                            <i class="ti ti-edit"></i> Editar perfil
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="*:py-4 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                        <li class="list-group-item px-0 pt-0">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 md:col-span-6">
                                    <p class="mb-1 text-muted">Nome Completo</p>
                                    <p class="mb-0">{{ $user->fullname }}</p>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <p class="mb-1 text-muted">E-mail</p>
                                    <p class="mb-0">{{ $user->email }}</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 md:col-span-6">
                                    <p class="mb-1 text-muted">Telefone</p>
                                    <p class="mb-0">{{ getFormattedPhone($user->phone) }}</p>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <p class="mb-1 text-muted">Sexo</p>
                                    <p class="mb-0">{{ $user->getGender() }}</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 pt-0">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 md:col-span-6">
                                    <p class="mb-1 text-muted">Data de Nascimento</p>
                                    <p class="mb-0">
                                        {{ getFormattedDateTime($user->birthdate) }}
                                    </p>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <p class="mb-1 text-muted">Função</p>
                                    <p class="mb-0">{{ $user->getRoleLabel() }}</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 pt-0">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 md:col-span-6">
                                    <p class="mb-1 text-muted">Data de Criação</p>
                                    <p class="mb-0"> {{ getFormattedDateTime($user->created_at) }}</p>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <p class="mb-1 text-muted">Data de Edição</p>
                                    <p class="mb-0"> {{ getFormattedDateTime($user->updated_at) }}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('custom-scripts')
    {{-- Calls --}}
    <script>
        $(document).ready(function() {
            // Image modal preview setup
            setupImagePreview('#profilePhotoInput', '#profilePreview', 'photoMessage');
            uploadUserPhoto('/admin/profile/user-photo-upload', '/admin/profile');
        });
    </script>
@endsection
