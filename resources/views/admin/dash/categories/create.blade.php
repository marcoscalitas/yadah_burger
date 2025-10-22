@extends('admin.dash.layouts.main')

@section('title', 'Adicionar Categoria')

@section('breadcrumb')
    @include('admin.dash.components.breadcrumb', getBreadcrumb('admin.categories.create'))
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                                        name="name" value="{{ old('name') }}" />
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
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger d-flex align-items-center mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- User image -->
                            <div class="col-span-12 sm:col-span-12">
                                <div class="mb-1">
                                    <label class="form-label">
                                        Escolher imagem
                                        <span class="text-danger">*</span>
                                    </label>
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
                    <button type="submit" class="btn btn-primary">Adicionar Categoria</button>
                </div>
            </form>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
