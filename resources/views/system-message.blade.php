@if(Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Woops!</strong>  {{ Session::get('error')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if(Session::has('response'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong>  {{ Session::get('response')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
