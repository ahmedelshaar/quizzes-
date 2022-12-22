@if(session()->has('success'))
    <div class="alert alert-dismissible bg-success text-white border-0" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        {{ session()->get('success') }}
    </div>
@endif
