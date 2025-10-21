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
                        <h5 class="mb-3 sm:mb-0">Lista de utilizadores apagados</h5>
                        <div>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                Adicionar Utilizador
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
                                            <th data-sortable="true" style="width: 6%;">
                                                <button class="datatable-sorter">#</button>
                                            </th>
                                            <th data-sortable="true" style="width: 22.605694564279553%;">
                                                <button class="datatable-sorter">Utilizador</button>
                                            </th>
                                            <th data-sortable="true" style="width: 18.809318377911993%;">
                                                <button class="datatable-sorter">E-mail</button>
                                            </th>
                                            <th data-sortable="true" style="width: 18.809318377911993%;">
                                                <button class="datatable-sorter">Telefone</button>
                                            </th>
                                            <th data-sortable="true" style="width: 15.875754961173424%;">
                                                <button class="datatable-sorter">Sexo</button>
                                            </th>
                                            <th data-sortable="true" style="width: 13.805004314063849%;">
                                                <button class="datatable-sorter">Função</button>
                                            </th>
                                            <th data-sortable="true" style="width: 14.667817083692839%;">
                                                <button class="datatable-sorter">Estado</button>
                                            </th>
                                            <th data-sortable="true" style="width: 14.236410698878343%;">
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
                                                            <h6 class="mb-0">{{ $user->getShortName() }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->getFormattedPhone() }}</td>
                                                <td>{{ $user->getGender() }}</td>
                                                <td>{{ $user->getRoleLabel() }}</td>
                                                <td>{!! getStatusBadge($user->user_status) !!}</td>
                                                <td class="d-flex gap-2">
                                                    {{-- Restaurar --}}
                                                    <a href="{{ route('admin.users.restore', $user->id) }}"
                                                        class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                        <i class="ti ti-edit text-xl leading-none"></i>
                                                    </a>

                                                    {{-- Excluir --}}
                                                    <button type="button"
                                                        class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary"
                                                        data-pc-toggle="modal"
                                                        data-pc-target="#deleteUserModal{{ $user->id }}"
                                                        data-pc-animate="sticky-up">
                                                        <i class="ti ti-trash text-xl leading-none"></i>
                                                    </button>
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
                                                                    <i class="fas fa-user me-2"></i> Apagar Utilizador
                                                                </h5>
                                                                <button type="button"
                                                                    data-pc-modal-dismiss="#deleteUserModal{{ $user->id }}"
                                                                    class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                                                                    <i class="ti ti-x"></i>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="flex items-center gap-3 mb-4">
                                                                    <div class="shrink-0">
                                                                        <img class="shrink-0 w-[100px] h-[100px] round-image"
                                                                            src="{{ $user->getImageUrl() }}" alt="produto"
                                                                            style="height: 50px; width: 50px;" />
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="font-semibold">
                                                                            {{ $user->getShortName() }}</h6>
                                                                    </div>
                                                                </div>
                                                                <p class="text-muted">
                                                                    Tem certeza de que deseja
                                                                    <span class="text-danger">
                                                                        <strong>apagar</strong>
                                                                    </span>
                                                                    este utilizador de forma
                                                                    <span class="text-danger">
                                                                        <strong> permanente?</strong>
                                                                    </span>
                                                                    Esta ação não poderá ser desfeita.
                                                                </p>
                                                            </div>

                                                            <div class="modal-footer flex justify-end gap-3 border-t">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-pc-modal-dismiss="#deleteUserModal{{ $user->id }}">
                                                                    Cancelar
                                                                </button>
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-trash me-2"></i> Apagar
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
                                                        <i class="ti ti-tag text-6xl text-gray-300 mb-4"></i>
                                                        <h5 class="text-gray-500 mb-2">Nenhum utilizador encontrado.</h5>
                                                        <p class="text-gray-400 mb-4">Ainda não há utilizadores cadastrados
                                                            no
                                                            sistema.</p>
                                                        <a href="{{ route('admin.users.create') }}"
                                                            class="btn btn-primary">
                                                            <i class="ti ti-plus me-2"></i>Adicionar primeiro utilizador
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
