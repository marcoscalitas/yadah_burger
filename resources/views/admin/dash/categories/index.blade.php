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
                        <h5 class="mb-3 sm:mb-0">Lista de categorias</h5>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.categories.trashed') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-trash me-2"></i>Ver categorias eliminadas
                            </a>
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
                                                            <h6 class="mb-0">{{ getShortText($category->name, 25) }}</h6>
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
                                                    {{ $category->createdBy ? getShortText(getShortName($category->createdBy->name), 15) : 'Sistema' }}
                                                </td>
                                                <td>{!! getFormattedDateTime($category->created_at, 'datetime') !!}</td>
                                                <td>
                                                    <div class="flex gap-3 justify-content-center">
                                                        {{-- Editar --}}
                                                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-warning"
                                                            title="Editar">
                                                            <i class="ti ti-edit text-xl leading-none"></i>
                                                        </a>

                                                        {{-- Excluir --}}
                                                        <button type="button"
                                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-danger"
                                                            data-pc-toggle="modal"
                                                            data-pc-target="#deleteCategoryModal{{ $category->id }}"
                                                            data-pc-animate="sticky-up"
                                                            title="Excluir">
                                                            <i class="ti ti-trash text-xl leading-none"></i>
                                                        </button>
                                                    </div>
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
                                                            action="{{ route('admin.categories.destroy', $category->id) }}">
                                                            @csrf
                                                            @method('DELETE')

                                                            <div class="modal-header">
                                                                <h5 class="modal-title font-semibold text-danger text-lg">
                                                                Eliminar Categoria
                                                                </h5>
                                                                <button type="button"
                                                                    data-pc-modal-dismiss="#deleteCategoryModal{{ $category->id }}"
                                                                    class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                                                                    <i class="ti ti-x"></i>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body text-center">
                                                                <div class="mb-4">
                                                                    <img class="shrink-0 w-[100px] h-[100px] round-image mx-auto mb-3"
                                                                        src="{{ $category->getImageUrl() }}"
                                                                        alt="categoria"
                                                                        style="height: 80px; width: 80px;" />
                                                                    <h6 class="font-semibold mb-0">{{ $category->name }}</h6>
                                                                    <span class="text-sm text-muted">
                                                                        {{ getShortText($category->description) }}
                                                                    </span>
                                                                </div>
                                                                <p class="text-muted">
                                                                    <strong>Deseja realmente <span class="text-danger">eliminar</span> esta categoria?</strong>
                                                                </p>
                                                                <p class="text-muted">
                                                                    Esta ação só pode ser desfeita por um administrador.
                                                                </p>
                                                            </div>

                                                            <div class="modal-footer flex justify-end gap-3 border-t">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-pc-modal-dismiss="#deleteCategoryModal{{ $category->id }}">
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
                                                <td colspan="7" class="text-center py-8">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <i class="ti ti-tag text-6xl text-gray-300 mb-4"></i>
                                                        <h5 class="text-gray-500 mb-2">Nenhuma categoria encontrada</h5>
                                                        <p class="text-gray-400 mb-4">Ainda não há categorias cadastradas
                                                            no
                                                            sistema.</p>
                                                        <a href="{{ route('admin.categories.create') }}"
                                                            class="btn btn-primary">
                                                            <i class="ti ti-plus me-2"></i>Adicionar primeira categoria
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
