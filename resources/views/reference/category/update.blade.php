@extends('interface.main')
@section('title','Update Product Category')
@section('styles')
    <!-- Custom styles for this page -->

@endsection
@section('main')
    <a href="{{route('category.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Category List</a>
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="card shadow-sm mt-2">
            <div class="card-header font-weight-bold text-primary">
                Update Product Category
            </div>
            <div class="card-body">
                @include('interface.system-messages')
                <form action="{{ route('category.update', $category->id) }}" method="post" role="form">
                    @csrf
                    <div class="form-group">
                        <div class="font-weight-bold">Category Name </div>
                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" placeholder="Category Name here" required>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('category.list')}}" class="btn btn-danger"><i class="fas fa fa-times"></i> Cancel</a>
                    <button type="submit" class="btn btn-success float-right"><i class="fas fa-fw fa-check"></i>Apply Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
