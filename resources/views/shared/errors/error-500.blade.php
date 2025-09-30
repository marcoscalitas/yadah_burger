@extends('shared.layouts.main')

@section('title', 'Página não encontrada')

@section('content')
    <div class="maintenance-block min-h-screen w-full flex items-center justify-center">
        <div class="container">
            <div class="card error-card bg-transparent dark:bg-transparent shadow-none border-none">
                <div class="card-body">
                    <div class="error-image-block">
                        <img class="img-fluid mx-auto" src="{{ asset('admin/assets/images/pages/img-error-500.svg') }}"
                            alt="img" />
                    </div>
                    <div class="text-center">
                        <h1 class="mt-4"><b>Internal Server Error</b></h1>
                        <p class="mt-2 mb-4 text-sm text-muted">Server error 500. we fixing the problem. please<br />
                            try again at a later stage.</p>
                        <a href="../dashboard/index.html" class="btn btn-primary mb-3">Go to homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
