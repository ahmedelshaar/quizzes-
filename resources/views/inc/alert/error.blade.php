@if(session()->has('error'))
<div class="alert alert-dismissible bg-danger text-white border-0 fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    {{ session()->get('error') }}
</div>
@endif
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-dismissible bg-danger text-white border-0 fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            {{ $error }}
        </div>
    @endforeach
@endif
