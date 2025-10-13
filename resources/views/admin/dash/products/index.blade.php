@extends('admin.dash.layouts.main')

@section('title', 'Produtos')

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.products.index'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <h5 class="mb-3 sm:mb-0">Lista de produtos</h5>
                        <div>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-2"></i>Adicionar Produto
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
                                            <th data-sortable="true" style="width: 30%;">
                                                <button class="datatable-sorter">Produto</button>
                                            </th>
                                            <th data-sortable="true" style="width: 20%;">
                                                <button class="datatable-sorter">Categoria</button>
                                            </th>
                                            <th data-sortable="true" style="width: 15%;">
                                                <button class="datatable-sorter">Status</button>
                                            </th>
                                            <th data-sortable="true" style="width: 15%;">
                                                <button class="datatable-sorter">Preço</button>
                                            </th>
                                            <th data-sortable="true" style="width: 14%;">
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
                                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm text-gray-600">{{ $product->category->name ?? 'Sem categoria' }}</span>
                                                </td>
                                                <td>{!! getStatusBadge($product->product_status) !!}</td>
                                                <td>{{ number_format($product->price, 2, ',', '.') . ' Kz' }}</td>
                                                <td class="d-flex gap-2">
                                                    {{-- Editar --}}
                                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                                        class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                        <i class="ti ti-edit text-xl leading-none"></i>
                                                    </a>

                                                    {{-- Excluir --}}
                                                    <button type="button"
                                                        class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary"
                                                        data-pc-toggle="modal"
                                                        data-pc-target="#deleteProductModal{{ $product->id }}"
                                                        data-pc-animate="sticky-up">
                                                        <i class="ti ti-trash text-xl leading-none"></i>
                                                    </button>
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
                                                            action="{{ route('admin.products.destroy', $product->id) }}">
                                                            @csrf
                                                            @method('DELETE')

                                                            <div class="modal-header">
                                                                <h5 class="modal-title font-semibold text-danger text-lg">
                                                                    <i class="fas fa-tag me-2"></i> Confirmar exclusão
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
                                                                        <span
                                                                            class="text-sm text-gray-500">{{ $product->getShortDescription(50) }}</span>
                                                                    </div>
                                                                </div>
                                                                <p class="text-muted">
                                                                    Tem certeza de que deseja
                                                                    <strong>
                                                                        <span class="text-danger">excluir</span>
                                                                    </strong>
                                                                    esta categoria? Esta ação não pode ser desfeita.
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer flex justify-center gap-3 border-t">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-pc-modal-dismiss="#deleteProductModal{{ $product->id }}">
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
                                                <td colspan="6" class="text-center py-8">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <i class="ti ti-tag text-6xl text-gray-300 mb-4"></i>
                                                        <h5 class="text-gray-500 mb-2">Nenhum produto encontrado</h5>
                                                        <p class="text-gray-400 mb-4">Ainda não há produtos cadastrados no
                                                            sistema.</p>
                                                        <a href="{{ route('admin.products.create') }}"
                                                            class="btn btn-primary">
                                                            <i class="ti ti-plus me-2"></i>Adicionar primeiro produto
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
