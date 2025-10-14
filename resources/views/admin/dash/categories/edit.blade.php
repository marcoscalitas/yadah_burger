@extends('admin.dash.layouts.main')

@section('title', 'Adicionar Categoria')

@section('breadcrumb')
    @include(
        'admin.dash.components.breadcrumb',
        getBreadcrumb(
            'admin.categories.edit',
            [['label' => 'Editar Categoria', 'url' => route('admin.categories.edit', $category->id)]],
            'Editar categoria'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h5>Adicionar Categoria</h5>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-12 gap-6">
                            <!-- Nome -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Nome
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $category->name) }}" />
                                    @error('name')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Descrição da Categoria -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Descrição
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                                    @error('description')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Image -->
                            <div class="col-span-12 sm:col-span-12">
                                <div class="mb-1">
                                    <label class="form-label">Escolher foto</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        name="image" accept="image/*" />
                                    @error('image')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    @if ($category->image_url)
                                        <div class="mt-3">
                                            <small class="text-muted">Imagem atual:</small><br>

                                            <img class="mt-2 shrink-0 w-[100px] h-[100px] round-image"
                                                src="{{ $category->getImageUrl() }}" alt="Imagem atual"
                                                style="height: 120px; width: 120px;" />
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Criado por -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Criado por
                                    </label>
                                    <input type="text" name="created_by_display" class="form-control disabled-field" disabled
                                        value="{{ $category->createdBy ? $category->createdBy->fullname : 'Sistema' }}" />
                                </div>
                            </div>

                            <!-- Editado por -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Editado por
                                    </label>
                                    <input type="text" name="updated_by_display" class="form-control disabled-field" disabled
                                        value="{{ $category->updatedBy ? $category->updatedBy->fullname : 'Sistema' }}" />
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
                                        value="{{ old('role', $category->created_at->format('Y-m-d')) }}" />
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
                                        value="{{ old('role', $category->updated_at->format('Y-m-d')) }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 text-right">
                        <button type="submit" class="btn btn-primary">Editar Categoria</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
