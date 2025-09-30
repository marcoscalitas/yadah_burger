@extends('shared.layouts.main')

@section('title', 'Página não encontrada')

@section('content')
    <div class="maintenance-block min-h-screen w-full flex items-center justify-center">
        <div class="container">
            <div class="card error-card bg-transparent dark:bg-transparent shadow-none border-none">
                <div class="card-body">
                    <div class="error-image-block">
                        <img class="img-fluid mx-auto" src="{{ asset('admin/assets/images/pages/img-error-404.svg') }}"
                            alt="img" />
                    </div>
                    <div class="text-center">
                        <h1 class="mt-5"><b>Página não encontrada</b></h1>
                        <p class="mt-2 mb-4 text-muted">A página que você está procurando foi movida, removida,
                            renomeada ou pode nunca existir!
                        </p>
                        <a href="{{ route('admin.index') }}" class="btn btn-primary mb-3">Voltar para a home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
