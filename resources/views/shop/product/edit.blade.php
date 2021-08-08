@extends('interface.main')
@section('title','ProductOld')
@section('styles')
@endsection
@section('main')
    <a href="{{route('product.show', $product->id)}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Product Collection</a>
    <div class="w-75">
        <div class="card shadow-sm mt-3">
            <div class="card-header font-weight-bold text-primary">
                <i class="fas fa-fw fa-shopping-bag"></i> Product Details
            </div>
            <div class="card-body">
                @include('interface.system-messages')
                <h5 class="font-weight-bold">Update details for {{ $product->item_title }}</h5>
                <form action="{{ route('product.update', $product->id) }}" method="post" role="form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-3">
                            <label for="" class="font-weight-bold">Product Title </label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            <input type="text" name="title" class="form-control" placeholder="Product title here . . . ." value="{{ $product->title }}" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <label for="" class="font-weight-bold">Product Image</label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            @if ($product->uploaded_img)
                            <div class="font-weight-bold">Current Image</div>
                            <img src="{{ $product->uploaded_img }}" id="image" width="100px" height="100px" alt="">
                            @else    
                                <small>No Image Available</small>
                            @endif
                            <br>
                            <input type="file" name="image" class="form-control-file mt-2">
                            <small class="text-danger font-italic">Click <b>Choose File</b> to replace and upload new image for the Product</small>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <label for="" class="font-weight-bold">Product Description</label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            <textarea name="description" id="" cols="30" rows="3" value="{{ $product->description }}" class="form-control"></textarea>
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
                                @foreach( $categories as $category )
                                    <option value="{{ $category->id }}"
                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
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
                                    {{ $product->unit_id == $unit->id ? 'selected' : '' }}>
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
                            <input type="number" name="price" step=".01" value="{{ $product->price }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <label for="" class="font-weight-bold">Purchase Points</label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            <input type="number" name="points" value="{{ $product->points }}" step=".01" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <label for="alert-level" class="font-weight-bold">Alert level Quantity</label>
                            <span class="float-right font-weight-bold">:</span>
                        </div>
                        <div class="col-8">
                            <input type="number" id="alert-level" min="0" name="alert_level" value="{{ $product->alert_level }}" class="form-control">
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success form-control mt-4">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection

