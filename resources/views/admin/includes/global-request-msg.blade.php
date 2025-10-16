<!-- [ Messages ] start -->
@if (session()->has('success'))
    <div class="alert alert-success message-fade-out">
        <span>
            <i class="fas fa-check-circle fa-lg me-2"></i>
        </span>
        {{ session('success') }}
    </div>
@endif
{{-- @if (session()->has('error'))
                <div class="alert alert-danger message-fade-out">
                    <span>
                        <i class="fas fa-exclamation-circle fa-lg me-2"></i>
                    </span>
                    {{ session('error') }}
                </div>
            @endif --}}
@if ($errors->any() && !$errors->has('error'))
    <div class="alert alert-danger message-fade-out">
        <span>
            <i class="fas fa-exclamation-circle fa-lg me-2"></i>
        </span>
        <strong>Por favor corrija os seguintes erros:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!-- [ Messages ] end -->
