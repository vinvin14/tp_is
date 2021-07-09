@extends('interface.main')
@section('title','ProductOld')
@section('styles')
    <!-- Custom styles for this page -->

@endsection
@section('main')
    <a href="{{route('specification.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Specification List</a>
    <div class="card shadow-sm mt-2">
        <div class="card-header font-weight-bold text-primary">
            New Product Specification
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
            <form action="{{ route('specification.store') }}" method="post" role="form" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <label for="" class="font-weight-bold">Product Title</label>
                        <input type="text" name="item_title" class="form-control" placeholder="Product title here . . . .">
                    </div>
                    <div class="col-6">
                        <label for="" class="font-weight-bold">Product Image</label>
                        <input type="file" name="image" class="form-control-file">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-3">
                        <label for="" class="font-weight-bold">Product Category</label>
                        <select name="product_category" id="" class="form-control" required>
                            <option value="">-</option>
                            @foreach( $product_categories as $category )
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="" class="font-weight-bold">Product Unit</label>
                        <select name="unit" id="" class="form-control" required>
                            <option value="">-</option>
                            @foreach( $units as $unit )
                                <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="" class="font-weight-bold">Product Price</label>
                        <input type="number" name="price" step=".01" class="form-control" required>
                    </div>
                    <div class="col-3">
                        <label for="" class="font-weight-bold">Purchase Points</label>
                        <input type="number" name="points" step=".01" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success mt-3 form-control">Add New Specification</button>
            </form>
        </div>
    </div>
@endsection
