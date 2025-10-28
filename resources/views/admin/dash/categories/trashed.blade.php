@extends('admin.dash.layouts.main')

@section('title', 'Categorias')

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.categories.index'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <h5 class="mb-3 sm:mb-0">Lista de categorias eliminadas</h5>
                        <div>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-2"></i>Adicionar Categoria
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
                                            <th data-sortable="true" style="width: 25%;">
                                                <button class="datatable-sorter">Categoria</button>
                                            </th>
                                            <th data-sortable="true" style="width: 20%;">
                                                <button class="datatable-sorter">Descrição</button>
                                            </th>
                                            <th data-sortable="true" style="width: 10%;">
                                                <button class="datatable-sorter">Status</button>
                                            </th>
                                            <th data-sortable="true" style="width: 15%;">
                                                <button class="datatable-sorter">Criada por</button>
                                            </th>
                                            <th data-sortable="true" style="width: 15%;">
                                                <button class="datatable-sorter">Data de Criação</button>
                                            </th>
                                            <th data-sortable="true" style="width: 10%;">
                                                <button class="datatable-sorter">Ações</button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($categories as $index => $category)
                                            <tr data-index="{{ $index }}" data-id="{{ $category->id }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="flex items-center w-44">
                                                        <div class="shrink-0">
                                                            <img class="shrink-0 w-[100px] h-[100px] round-image"
                                                                src="{{ $category->getImageUrl() }}" alt="user-image"
                                                                style="height: 50px; width: 50px;" />
                                                        </div>

                                                        <div class="grow ltr:ml-3 rtl:mr-3">
                                                            <h6 class="mb-0">{{ $category->name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="max-w-xs overflow-hidden">
                                                        <span class="text-sm text-gray-600 block truncate"
                                                            title="{{ $category->description }}">
                                                            {{ getShortText($category->description) }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>{!! getStatusBadge($category->category_status) !!}</td>
                                                <td>
                                                    {{ $category->createdBy ? $category->createdBy->getShortName() : 'Sistema' }}
                                                </td>
                                                <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="d-flex gap-2">
                                                    {{-- Restaurar --}}
                                                    <form method="POST"
                                                        action="{{ route('admin.categories.restore', $category->id) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary"
                                                            title="Restaurar categoria">
                                                            <i class="ti ti-refresh text-xl leading-none"></i>
                                                        </button>
                                                    </form>

                                                    {{-- Excluir --}}
                                                    <button type="button"
                                                        class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary"
                                                        data-pc-toggle="modal"
                                                        data-pc-target="#deleteCategoryModal{{ $category->id }}"
                                                        data-pc-animate="sticky-up">
                                                        <i class="ti ti-trash text-xl leading-none"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Modal de Confirmação de Exclusão -->
                                            <div id="deleteCategoryModal{{ $category->id }}" class="modal fade"
                                                tabindex="-1" role="dialog"
                                                aria-labelledby="deleteCategoryModalLabel{{ $category->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST"
                                                            action="{{ route('admin.categories.force.destroy', $category->id) }}">
                                                            @csrf
                                                            @method('DELETE')

                                                            <div class="modal-header">
                                                                <h5 class="modal-title font-semibold text-danger text-lg">
                                                                    <i class="fas fa-tag me-2"></i> Confirmar exclusão
                                                                </h5>
                                                                <button type="button"
                                                                    data-pc-modal-dismiss="#deleteCategoryModal{{ $category->id }}"
                                                                    class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                                                                    <i class="ti ti-x"></i>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="flex items-center gap-3 mb-4">
                                                                    <div class="shrink-0">
                                                                        <img class="shrink-0 w-[100px] h-[100px] round-image"
                                                                            src="{{ $category->getImageUrl() }}"
                                                                            alt="categoria"
                                                                            style="height: 50px; width: 50px;" />
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="font-semibold">{{ $category->name }}
                                                                        </h6>
                                                                        <span class="text-sm text-gray-500">
                                                                            {{ getShortText($category->description, 50) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <p class="text-muted">
                                                                    Tem certeza de que deseja
                                                                    <span class="text-danger">
                                                                        <strong>apagar</strong>
                                                                    </span>
                                                                    esta categoria de forma
                                                                    <span class="text-danger">
                                                                        <strong> permanente?</strong>
                                                                    </span>
                                                                    Esta ação não poderá ser desfeita.
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer flex justify-end gap-3 border-t">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-pc-modal-dismiss="#deleteCategoryModal{{ $category->id }}">
                                                                    Cancelar
                                                                </button>
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-trash me-2"></i> Excluir
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
                                                        <h5 class="text-gray-500 mb-2">Nenhuma categoria encontrada.</h5>
                                                        <p class="text-gray-400 mb-4">
                                                            Ainda não há categorias eliminadas no sistema.
                                                        </p>
                                                        <a href="{{ route('admin.categories.index') }}"
                                                            class="btn btn-danger">
                                                            <i class="fas fa-trash me-2"></i>Eliminar primeira categoria
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
