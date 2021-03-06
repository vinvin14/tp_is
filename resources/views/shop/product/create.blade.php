@extends('interface.main')
@section('title','Product Create')
@section('styles')
    <!-- Custom styles for this page -->

@endsection
@section('main')
    <a href="{{route('product.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Product List</a>
    <div class="col-xs-12 col-md-6 col-lg-6 ">
        <div class="card shadow-sm mt-2">
            <div class="card-header font-weight-bold text-primary">
                <i class="fas fa-fw fa-shopping-bag"></i> New Product
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
                <form action="{{ route('product.store') }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-3">
                            <label for="" class="font-weight-bold">Product Title </label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            <input type="text" name="title" class="form-control" placeholder="Product title here . . . ." value="{{ old('title') }}" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <label for="" class="font-weight-bold">Product Image</label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            <input type="file" name="image" class="form-control-file">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <label for="" class="font-weight-bold">Product Description</label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            <textarea name="description" id="" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <label for="" class="font-weight-bold">Product Category</label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            <select name="category_id" id="" class="form-control" required>
                                <option value="">-</option>
                                @foreach( $product_categories as $category )
                                    <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <label for="" class="font-weight-bold">Product Unit</label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            <select name="unit_id" id="" class="form-control" required>
                                <option value="">-</option>
                                @foreach( $units as $unit )
                                    <option value="{{ $unit->id }}"
                                    {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <label for="" class="font-weight-bold">Product Price</label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            <input type="number" name="price" step=".01" value="{{ old('price') }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <label for="" class="font-weight-bold">Purchase Points</label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            <input type="number" name="points" value="{{ old('points') }}" step=".01" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <label for="min-quantity" class="font-weight-bold">Alert level Quantity</label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            <input type="number" id="alert-level" min="0" name="alert_level" value="{{ old('alert_level') }}" class="form-control">
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success mt-3 form-control">Add New Product</button>
                </form>
            </div>
        </div>
    </div>
@endsection
