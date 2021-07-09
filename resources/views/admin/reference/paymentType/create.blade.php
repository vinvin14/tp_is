@extends('interface.main')
@section('title','Create Payment Type')
@section('styles')
    <!-- Custom styles for this page -->

@endsection
@section('main')
    <a href="{{route('paymentType.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Payment Type List</a>
    <div class="col-6">
        <div class="card shadow-sm mt-2">
            <div class="card-header font-weight-bold text-primary">
                New Payment Type
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
                <form action="{{ route('paymentType.store') }}" method="post" role="form">
                    @csrf
                    <div class="row text-left">
                        <div class="col-3">
                            <div class="font-weight-bold">Type Name </div>
                        </div>
                        <div class="col-9">
                            <input type="text" name="type_name" class="form-control" value="{{ old('type_name') }}" placeholder="Payment Type Name here" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mt-3 float-right"><i class="fas fa-fw fa-plus"></i>Add Payment Type</button>
                </form>
            </div>
        </div>
    </div>
@endsection
