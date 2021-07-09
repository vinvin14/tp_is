@extends('interface.main')
@section('title','Add Product Quantity')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    <a href="{{route('product.show', $productQuantity->product_id)}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Product</a>
    <div class="card shadow-sm w-50 mt-2">
        <div class="card-header font-weight-bold">
            Add Quantity for ({{$product->item_title}})
        </div>
        <div class="card-body">
            @include('system-message')
            <form action="{{ route('product.quantity.upsave', $productQuantity->id) }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="received-date">Received Date</label>
                    <input type="date" id="received-date" name="received_date" value="{{ $productQuantity->received_date }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="expiration">Expiration Date</label>
                    <input type="date" id="expiration" name="expiration_date" value="{{ $productQuantity->expiration_date }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="{{ $productQuantity->quantity }}" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
@endsection
