@extends('interface.main')
@section('title','Create Claim Type')
@section('styles')
    <!-- Custom styles for this page -->

@endsection
@section('main')
    <a href="{{route('claimType.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Claim Type List</a>
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="card shadow-sm mt-2">
            <div class="card-header font-weight-bold text-primary">
                New Claim Type
            </div>
            <div class="card-body">
                @include('interface.system-messages')
                <form action="{{ route('claimType.store') }}" method="post" role="form">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-bold">Claim Type Name </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Claim Type here" required>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 float-right"><i class="fas fa-fw fa-plus"></i>Add New Claim Type</button>
                </form>
            </div>
        </div>
    </div>
@endsection
