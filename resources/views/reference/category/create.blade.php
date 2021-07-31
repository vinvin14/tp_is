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
                <form action="{{ route('category.store') }}" method="post" role="form">
                    @csrf
                    <div class="row text-left">
                        <div class="col-3">
                            <div class="font-weight-bold">Category Name </div>
                        </div>
                        <div class="col-9">
                            <input type="text" name="category_name" class="form-control" value="{{ old('category_name') }}" placeholder="Unit Name here" required>
                        </div>
                    </div>
                    <div class="row mt-3 text-left">
                        <div class="col-3">
                            <div class="font-weight-bold">Description</div>
                        </div>
                        <div class="col-9">
                            <textarea name="description" id="" cols="30" rows="10" class="form-control">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 float-right"><i class="fas fa-fw fa-plus"></i>Add New Category</button>
                </form>
            </div>
        </div>
    </div>
@endsection
