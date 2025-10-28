@extends('admin.dash.layouts.main')

@section('title', 'Adicionar Produto')

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.products.create'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h5>Adicionar Produto</h5>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-12 gap-6">
                            <!-- Name -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Nome <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" />
                                    @error('name')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Categoria <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                        name="category_id">
                                        <option value="">Selecione</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Descrição da Categoria -->
                            <div class="col-span-12 sm:col-span-12">
                                <div class="mb-1">
                                    <label class="form-label"> Descrição</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Promotion Price -->
                            <div class="col-span-12 sm:col-span-4">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Preço <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('price') is-invalid @enderror"
                                        name="price" id="price" value="{{ old('price') }}" />
                                    @error('price')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Is featured -->
                            <div class="col-span-12 sm:col-span-4">
                                <div class="mb-1">
                                    <label class="form-label">Em promoção <span class="text-danger">*</span></label>
                                    <select class="form-select @error('is_featured') is-invalid @enderror"
                                        name="is_featured">
                                        <option value="">Selecione</option>
                                        <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>
                                            Sim</option>
                                        <option value="0" {{ old('is_featured') == '0' ? 'selected' : '' }}>
                                            Não</option>
                                    </select>
                                    @error('is_featured')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-span-12 sm:col-span-4">
                                <div class="mb-1">
                                    <label class="form-label">Preço promocional <span class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('promotion_price') is-invalid @enderror"
                                        name="promotion_price" id="promotion-price" value="{{ old('promotion_price') }}" />
                                    @error('promotion_price')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 text-right">
                    <button type="submit" class="btn btn-primary">Adicionar Produto</button>
                </div>
            </form>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function() {
            formatPriceField('price');
            formatPriceField('promotion-price');

            togglePromotionPrice();
        });
    </script>
@endsection
