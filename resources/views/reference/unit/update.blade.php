@extends('interface.main')
@section('title','Unit Update')
@section('styles')
    <!-- Custom styles for this page -->

@endsection
@section('main')
    <a href="{{route('unit.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Unit List</a>
    <div class="col-6">
        <div class="card shadow-sm mt-2">
            <div class="card-header font-weight-bold text-primary">
                New Product Unit
            </div>
            <div class="card-body">
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
                <form action="{{ route('unit.upsave', $unit->id) }}" method="post" role="form">
                    @csrf
                    <div class="row text-left">
                        <div class="col-3">
                            <div class="font-weight-bold">Unit Name </div>
                        </div>
                        <div class="col-9">
                            <input type="text" name="unit_name" class="form-control" value="{{ $unit->unit_name }}" placeholder="Unit Name here" required>
                        </div>
                    </div>
                    <div class="row mt-3 text-left">
                        <div class="col-3">
                            <div class="font-weight-bold">2nd Level Unit</div>
                        </div>
                        <div class="col-9">
                            <input type="text" name="second_level_unit" class="form-control" value="{{ $unit->second_level_unit }}" placeholder="Second Level Unit here">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 float-right"><i class="fas fa-fw fa-save"></i>Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
