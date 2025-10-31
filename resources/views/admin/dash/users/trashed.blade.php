@extends('admin.dash.layouts.main')

@section('title', 'Utilizadores')

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.users.trashed'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <h5 class="mb-3 sm:mb-0">Lista de utilizadores eliminados</h5>
                        <div>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                                Ver utilizadores ativos
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
                            <div class="datatable-container">
                                <table class="table table-hover datatable-table" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true" style="width: 5%;">
                                                <button class="datatable-sorter">#</button>
                                            </th>
                                            <th data-sortable="true" style="width: 22%;">
                                                <button class="datatable-sorter">Utilizador</button>
                                            </th>
                                            <th data-sortable="true" style="width: 20%;">
                                                <button class="datatable-sorter">E-mail</button>
                                            </th>
                                            <th data-sortable="true" style="width: 15%;">
                                                <button class="datatable-sorter">Telefone</button>
                                            </th>
                                            <th data-sortable="true" style="width: 10%;">
                                                <button class="datatable-sorter">Sexo</button>
                                            </th>
                                            <th data-sortable="true" style="width: 12%;">
                                                <button class="datatable-sorter">Função</button>
                                            </th>
                                            <th data-sortable="true" style="width: 10%;">
                                                <button class="datatable-sorter">Estado</button>
                                            </th>
                                            <th data-sortable="true" style="width: 6%;">
                                                <button class="datatable-sorter">Ação</button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $index => $user)
                                            <tr data-index="{{ $index }}" data-id="{{ $user->id }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="flex items-center w-44">
                                                        <div class="shrink-0">
                                                            <img class="shrink-0 w-[100px] h-[100px] round-image"
                                                                src="{{ $user->getImageUrl() }}" alt="user-image"
                                                                style="height: 50px; width: 50px;" />
                                                        </div>

                                                        <div class="grow ltr:ml-3 rtl:mr-3">
                                                            <h6 class="mb-0">{{ getShortName($user->fullname) }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ getFormattedPhone($user->phone) }}</td>
                                                <td>{{ $user->getGender() }}</td>
                                                <td>{{ $user->getRoleLabel() }}</td>
                                                <td>{!! getStatusBadge($user->user_status) !!}</td>
                                                <td>
                                                    <div class="flex gap-3 justify-content-center">
                                                        {{-- Restaurar --}}
                                                        <button type="button"
                                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-success"
                                                            data-pc-toggle="modal"
                                                            data-pc-target="#restoreUserModal{{ $user->id }}"
                                                            title="Restaurar usuário">
                                                            <i class="ti ti-refresh text-xl leading-none"></i>
                                                        </button>

                                                        {{-- Excluir Permanentemente --}}
                                                        <button type="button"
                                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-danger"
                                                            data-pc-toggle="modal"
                                                            data-pc-target="#deleteUserModal{{ $user->id }}"
                                                            data-pc-animate="sticky-up"
                                                            title="Excluir permanentemente">
                                                            <i class="ti ti-trash text-xl leading-none"></i>
                                                        </button>
                                                    </div>

                                                    {{-- Modal de Confirmação de Restauração --}}
                                                    <div class="modal fade modal-animate" id="restoreUserModal{{ $user->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title font-semibold">Confirmar restauração</h5>
                                                                    <button type="button"
                                                                        data-pc-modal-dismiss="#restoreUserModal{{ $user->id }}"
                                                                        class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                                                                        <i class="ti ti-x"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="text-center">
                                                                        <i class="ti ti-refresh text-success" style="font-size: 3rem;"></i>
                                                                        <h4 class="mt-3">Restaurar Utilizador</h4>
                                                                        <p class="text-muted">
                                                                            Tem certeza que deseja restaurar o utilizador
                                                                            <strong>{{ $user->name }}</strong>?
                                                                        </p>
                                                                        <p class="text-muted">
                                                                            O utilizador voltará para a lista de utilizadores ativos.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer flex justify-end gap-3 border-t">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-pc-modal-dismiss="#restoreUserModal{{ $user->id }}">Cancelar</button>
                                                                    <form method="POST"
                                                                        action="{{ route('admin.users.restore', $user->id) }}"
                                                                        style="display: inline;">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit" class="btn btn-success">
                                                                            <i class="ti ti-refresh me-2"></i>Sim, restaurar
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal de Confirmação de Exclusão -->
                                            <div id="deleteUserModal{{ $user->id }}" class="modal fade" tabindex="-1"
                                                role="dialog" aria-labelledby="deleteUserModalLabel{{ $user->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST"
                                                            action="{{ route('admin.users.force.destroy', $user->id) }}">
                                                            @csrf
                                                            @method('DELETE')

                                                            <div class="modal-header">
                                                                <h5 class="modal-title font-semibold text-danger text-lg">
                                                                    Eliminar Utilizador
                                                                </h5>
                                                                <button type="button"
                                                                    data-pc-modal-dismiss="#deleteUserModal{{ $user->id }}"
                                                                    class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                                                                    <i class="ti ti-x"></i>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body text-center">
                                                                <div class="mb-4">
                                                                    <img class="shrink-0 w-[100px] h-[100px] round-image mx-auto mb-3"
                                                                        src="{{ $user->getImageUrl() }}" alt="utilizador"
                                                                        style="height: 80px; width: 80px;" />
                                                                    <h6 class="font-semibold mb-0">
                                                                        {{ getShortName($user->fullname) }}</h6>
                                                                    <span class="text-muted text-sm">{{ $user->getRoleLabel() }}</span>
                                                                </div>
                                                                <p class="text-muted">
                                                                    <strong>Deseja realmente <span class="text-danger">eliminar</span> este utilizador?</strong>
                                                                </p>
                                                                <p class="text-muted">
                                                                    Esta ação não poderá ser desfeita.
                                                                </p>
                                                            </div>

                                                            <div class="modal-footer flex justify-end gap-3 border-t">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-pc-modal-dismiss="#deleteUserModal{{ $user->id }}">
                                                                    Cancelar
                                                                </button>
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-trash me-2"></i> Eliminar
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-8">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <i class="ti ti-user text-6xl text-gray-300 mb-4"></i>
                                                        <h5 class="text-gray-500 mb-2">Nenhum utilizador encontrado.</h5>
                                                        <p class="text-gray-400 mb-4">
                                                            Ainda não há utilizadores eliminados no sistema.
                                                        </p>
                                                        <a href="{{ route('admin.users.index') }}"
                                                            class="btn btn-danger">
                                                            <i class="fas fa-trash me-2"></i>Eliminar primeiro utilizador
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
