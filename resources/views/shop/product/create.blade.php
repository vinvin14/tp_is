@extends('interface.main')
@section('title','Product Create')
@section('styles')
    <!-- Custom styles for this page -->

@endsection
@section('main')
    <a href="{{route('product.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Product List</a>
    <div class="card w-75 shadow-sm mt-2">
        <div class="card-header font-weight-bold text-primary">
            New Product
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
                        <label for="" class="font-weight-bold">Product Titles </label>
                        <span class="float-right font-weight-bold">:</span>
                    </div>
                    <div class="col-8">
                        <input type="text" name="item_title" class="form-control" placeholder="Product title here . . . .">
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
                        <label for="" class="font-weight-bold">Product Category</label>
                        <span class="float-right font-weight-bold">:</span>
                    </div>
                    <div class="col-8">
                        <select name="product_category" id="" class="form-control" required>
                            <option value="">-</option>
                            @foreach( $product_categories as $category )
                                <option value="{{ $category->id }}"
                                {{ old('product_category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
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
                        <select name="unit" id="" class="form-control" required>
                            <option value="">-</option>
                            @foreach( $units as $unit )
                                <option value="{{ $unit->id }}"
                                {{ old('unit') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->unit_name }}</option>
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
                        <label for="min-quantity" class="font-weight-bold">Minimum Quantity</label>
                        <span class="float-right font-weight-bold">:</span>
                    </div>
                    <div class="col-8">
                        <input type="number" id="min-quantity" name="minimum_quantity" value="{{ old('minimum_quantity') }}" class="form-control">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <label for="" class="font-weight-bold">Current Quantity</label>
                        <span class="float-right font-weight-bold">:</span>
                    </div>
                    <div class="col-8">
                        <input type="number" name="current_quantity" class="form-control" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <label for="" class="font-weight-bold">Date Received</label>
                        <span class="float-right font-weight-bold">:</span>
                    </div>
                    <div class="col-8">
                        <input type="date" name="received_date" class="form-control" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <label for="" class="font-weight-bold">Date of Expiry</label>
                        <span class="float-right font-weight-bold">:</span>
                    </div>
                    <div class="col-8">
                        <input type="date" name="expiration_date" class="form-control" required>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-success mt-3 form-control">Add New Product</button>
            </form>
        </div>
    </div>
@endsection
