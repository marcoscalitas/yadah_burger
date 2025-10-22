@extends('admin.dash.layouts.main')

@section('title', 'Definições')

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.settings.index'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header flex items-center justify-between py-3">
                    <h5>Definições</h5>
                </div>
                <div class="card-body">
                    <div class="card shadow-none border border-theme-border dark:border-themedark-border">
                        <div class="card-header">
                            <div class="flex items-center">
                                <a href="{{ route('admin.index') }}" class="b-brand flex items-center gap-1">
                                    <!-- ========   Change your logo from here   ============ -->

                                    <img class="shrink-0 w-[55px] h-[55px] rounded-full"
                                        src="{{ asset('admin/assets/images/admin/img-add-user.png') }}" alt="Logo" />
                                </a>

                                <div class="grow mx-3">
                                    <h6 class="mb-0">Tomás Garcia</h6>
                                    <p class="mb-0 text-muted">tomasgarcia@gmail.com</p>
                                </div>
                                <div class="shrink-0">
                                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-sm btn-light-secondary"><i
                                            class="ti ti-edit"></i> Edit</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 md:col-span-6">
                                    <a href="{{ route('admin.settings.change.email') }}" class="block">
                                        <div
                                            class="card shadow-none border border-theme-border dark:border-themedark-border mb-0 h-full">
                                            <div class="card-body">
                                                <h2 class="mb-2 font-normal">
                                                    E-mail
                                                    <i class="ti ti-mail text-3xl text-primary-500"></i>
                                                </h2>
                                                <h6 class="mb-4 font-normal text-muted">
                                                    Atualize o seu endereço de e-mail para manter suas informações de
                                                    contato
                                                    corretas
                                                </h6>
                                                <div class="text-primary-500 flex items-center gap-2 hover:underline">
                                                    Alterar seu e-mail
                                                    <i class="ti ti-chevron-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <a href="{{ route('admin.settings.change.password') }}" class="block">
                                        <div
                                            class="card shadow-none border border-theme-border dark:border-themedark-border mb-0 h-full">
                                            <div class="card-body">
                                                <h2 class="mb-2 font-normal">
                                                    Senha
                                                    <i class="ti ti-lock text-3xl text-primary-500"></i>
                                                </h2>
                                                <h6 class="mb-4 font-normal text-muted">
                                                    Aumente a segurança da sua conta atualizando sua senha regularmente.
                                                </h6>
                                                <div class="text-primary-500 flex items-center gap-2 hover:underline">
                                                    Alterar sua senha
                                                    <i class="ti ti-chevron-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <div class="card shadow-none border border-theme-border dark:border-themedark-border mb-0">
                                <div class="card-body">
                                    <h6 class="mb-3 text-danger">
                                        <i class="fas fa-user-times me-2"></i> Excluir conta
                                    </h6>
                                    <p class="mb-3 text-muted">
                                        Deseja realmente excluir sua conta? <br />
                                        A exclusão é <strong>irreversível</strong> e removerá todo o conteúdo associado.
                                    </p>
                                    <!-- Botão abre o modal -->
                                    <button type="button" class="btn btn-danger" data-pc-toggle="modal"
                                        data-pc-target="#deleteAccountModal" data-pc-animate="sticky-up">
                                        <i class="fas fa-trash-alt me-1"></i> Excluir Conta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Excluir Conta -->
                    <div id="deleteAccountModal" class="modal fade" tabindex="-1" role="dialog"
                        aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.settings.delete.account') }}">
                                    @csrf
                                    @method('DELETE')

                                    <div class="modal-header">
                                        <h5 class="modal-title font-semibold text-danger text-lg">
                                            <i class="fas fa-user-times me-2"></i> Excluir Conta
                                        </h5>
                                        <button type="button" data-pc-modal-dismiss="#deleteAccountModal"
                                            class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body text-center">
                                        <p
                                            class="text-base text-gray-600 font-medium max-w-[420px] mx-auto leading-relaxed">
                                            Tem certeza de que deseja excluir sua conta?<br>
                                            Essa ação é <strong class="text-danger">permanente</strong> e não poderá ser
                                            desfeita.
                                        </p>
                                    </div>

                                    <div class="modal-footer flex justify-end gap-3 border-t">
                                        <button type="button" data-pc-modal-dismiss="#deleteAccountModal"
                                            class="btn btn-outline-secondary px-4">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-danger px-5">
                                            <i class="fas fa-trash-alt me-1"></i> Excluir definitivamente
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
