@if ($errors->any())
<div class="alert alert-danger">
    <div class="d-flex align-items-center mb-2">
        <svg class="me-2" style="width:24px;height:24px;color:#721c24" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <strong>Ups! Ada beberapa kesalahan.</strong>
    </div>
    <ul class="ps-4 mb-0">
        @foreach ($errors->all() as $error)
        <li class="mb-1">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
