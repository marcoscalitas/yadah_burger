@extends('admin.dash.layouts.main')

@section('title', 'Produtos')

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.products.trashed'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <h5 class="mb-3 sm:mb-0">Lista de produtos eliminados</h5>
                        <div>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-primary">
                                Ver produtos ativos
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
                                                <button class="datatable-sorter">Produto</button>
                                            </th>
                                            <th data-sortable="true" style="width: 12%;">
                                                <button class="datatable-sorter">Categoria</button>
                                            </th>
                                            <th data-sortable="true" style="width: 18%;">
                                                <button class="datatable-sorter">Descrição</button>
                                            </th>
                                            <th data-sortable="true" style="width: 12%;">
                                                <button class="datatable-sorter">Preço</button>
                                            </th>
                                            <th data-sortable="true" style="width: 10%;">
                                                <button class="datatable-sorter">Status</button>
                                            </th>
                                            <th data-sortable="true" style="width: 10%;">
                                                <button class="datatable-sorter">Criada por</button>
                                            </th>
                                            <th data-sortable="true" style="width: 8%;">
                                                <button class="datatable-sorter">Ações</button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $index => $product)
                                            <tr data-index="{{ $index }}" data-id="{{ $product->id }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="flex items-center w-44">
                                                        <div class="shrink-0">
                                                            <img class="shrink-0 w-[100px] h-[100px] round-image"
                                                                src="{{ $product->getImageUrl() }}" alt="user-image"
                                                                style="height: 50px; width: 50px;" />
                                                        </div>

                                                        <div class="grow ltr:ml-3 rtl:mr-3">
                                                            <h6 class="mb-0">{{ getShortText($product->name, 20) }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm text-gray-600">{{ $product->category->name ?? 'Sem categoria' }}</span>
                                                </td>
                                                <td>
                                                    <div class="max-w-xs overflow-hidden">
                                                        <span class="text-sm text-gray-600 block truncate"
                                                            title="{{ $product->description }}">
                                                            {{ getShortText($product->description, 30) }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>{!! getProductPrice($product) !!}</td>
                                                <td>{!! getStatusBadge($product->product_status) !!}</td>
                                                <td>
                                                    {{ $product->createdBy ? getShortText($product->createdBy->getShortName(), 15) : 'Sistema' }}
                                                </td>
                                                <td>
                                                    <div class="flex gap-3 justify-content-center">
                                                        {{-- Restaurar --}}
                                                        <button type="button"
                                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-success"
                                                            data-pc-toggle="modal"
                                                            data-pc-target="#restoreProductModal{{ $product->id }}"
                                                            title="Restaurar produto">
                                                            <i class="ti ti-refresh text-xl leading-none"></i>
                                                        </button>

                                                        {{-- Excluir Permanentemente --}}
                                                        <button type="button"
                                                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-danger"
                                                            data-pc-toggle="modal"
                                                            data-pc-target="#deleteProductModal{{ $product->id }}"
                                                            data-pc-animate="sticky-up"
                                                            title="Excluir permanentemente">
                                                            <i class="ti ti-trash text-xl leading-none"></i>
                                                        </button>
                                                    </div>

                                                    {{-- Modal de Confirmação de Restauração --}}
                                                    <div class="modal fade modal-animate" id="restoreProductModal{{ $product->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title font-semibold">Confirmar restauração</h5>
                                                                    <button type="button"
                                                                        data-pc-modal-dismiss="#restoreProductModal{{ $product->id }}"
                                                                        class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                                                                        <i class="ti ti-x"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="text-center">
                                                                        <i class="ti ti-refresh text-success" style="font-size: 3rem;"></i>
                                                                        <h4 class="mt-3">Restaurar Produto</h4>
                                                                        <p class="text-muted">
                                                                            Tem certeza que deseja restaurar o produto
                                                                            <strong>{{ $product->name }}</strong>?
                                                                        </p>
                                                                        <p class="text-muted">
                                                                            O produto voltará para a lista de produtos ativos.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer flex justify-end gap-3 border-t">
                                                                    <button type="button" class="btn btn-outline-secondary"
                                                                        data-pc-modal-dismiss="#restoreProductModal{{ $product->id }}">Cancelar</button>
                                                                    <form method="POST"
                                                                        action="{{ route('admin.products.restore', $product->id) }}"
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
                                            <div id="deleteProductModal{{ $product->id }}" class="modal fade"
                                                tabindex="-1" role="dialog"
                                                aria-labelledby="deleteProductModalLabel{{ $product->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST"
                                                            action="{{ route('admin.products.force.destroy', $product->id) }}">
                                                            @csrf
                                                            @method('DELETE')

                                                            <div class="modal-header">
                                                                <h5 class="modal-title font-semibold text-danger text-lg">
                                                                    Eliminar Produto
                                                                </h5>
                                                                <button type="button"
                                                                    data-pc-modal-dismiss="#deleteProductModal{{ $product->id }}"
                                                                    class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                                                                    <i class="ti ti-x"></i>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="flex items-center gap-3 mb-4">
                                                                    <div class="shrink-0">
                                                                        <img class="shrink-0 w-[100px] h-[100px] round-image"
                                                                            src="{{ $product->getImageUrl() }}"
                                                                            alt="produto"
                                                                            style="height: 50px; width: 50px;" />
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="font-semibold">{{ $product->name }}
                                                                        </h6>
                                                                        <span class="text-sm text-gray-500">
                                                                            {{ getShortText($product->description) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <p class="text-muted">
                                                                    Tem certeza de que deseja
                                                                    <span class="text-danger">
                                                                        <strong>eliminar</strong>
                                                                    </span>
                                                                    este produto de forma
                                                                    <span class="text-danger">
                                                                        <strong> permanente?</strong>
                                                                    </span>
                                                                    Esta ação não poderá ser desfeita.
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer flex justify-end gap-3 border-t">
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    data-pc-modal-dismiss="#deleteProductModal{{ $product->id }}">
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
                                                        <i class="ti ti-box text-6xl text-gray-300 mb-4"></i>
                                                        <h5 class="text-gray-500 mb-2">Nenhum produto encontrado.</h5>
                                                        <p class="text-gray-400 mb-4">
                                                            Ainda não há produtos eliminados no sistema.
                                                        </p>
                                                        <a href="{{ route('admin.products.index') }}"
                                                            class="btn btn-danger">
                                                            <i class="fas fa-trash me-2"></i>Eliminar primeiro produto
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
