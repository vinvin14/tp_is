@extends('interface.main')
@section('title','Create Product Category')
@section('styles')
    <!-- Custom styles for this page -->

@endsection
@section('main')
    <a href="{{route('category.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Category List</a>
    <div class="col-6">
        <div class="card shadow-sm mt-2">
            <div class="card-header font-weight-bold text-primary">
                New Product Category
            </div>
            <div class="card-body">
                @include('interface.system-messages')
                <form action="{{ route('category.store') }}" method="post" role="form">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-bold">Category Name </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Category Name here" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success mt-3 float-right"><i class="fas fa-fw fa-plus"></i>Add New Category</button>
                </form>
            </div>
        </div>
    </div>
@endsection
