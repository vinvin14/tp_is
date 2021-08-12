@extends('interface.main')
@section('title','Update Claim Type')
@section('styles')
    <!-- Custom styles for this page -->

@endsection
@section('main')
    <a href="{{route('claimType.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Claim Type List</a>
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="card shadow-sm mt-2">
            <div class="card-header font-weight-bold text-primary">
                Update Claim Type
            </div>
            <div class="card-body">
                @include('interface.system-messages')
                <form action="{{ route('claimType.update', $claimType->id) }}" method="post" role="form">
                    @csrf
                    <div class="form-group">
                        <div class="font-weight-bold">Claim Type Name </div>
                        <input type="text" name="name" class="form-control" value="{{ $claimType->name }}" placeholder="Category Name here" required>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('claimType.list')}}" class="btn btn-danger"><i class="fas fa fa-times"></i> Cancel</a>
                    <button type="submit" class="btn btn-success float-right"><i class="fas fa-fw fa-check"></i>Apply Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
