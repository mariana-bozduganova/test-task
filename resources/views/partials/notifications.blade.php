@if (session()->has('message'))
    <div class="alert alert-success" role="alert">
        <p>{{ session('message') }}</p>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger" role="alert">
        <p>{{ session('error') }}</p>
    </div>
@endif