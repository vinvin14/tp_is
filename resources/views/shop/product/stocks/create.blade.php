@extends('interface.main')
@section('title','Add Stock')
@section('styles')
    <!-- Custom styles for this page -->
    {{-- <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> --}}
@endsection
@section('main')
<a href="{{route('product.show', $product->id)}}" class="font-weight-bold"><i
    class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Current Product</a>
<div class="col-xs-12 col-md-6 col-lg-4">
    <div class="card shadow-sm mt-2">
        <div class="card-header font-weight-bold">Add Stock for {{ $product->title }}</div>
        <div class="card-body">
            <form action="{{ route('stock.store', $product->id) }}" method="post">
                <div class="form-group">
                    <label for="received_date">Date Received</label>
                    <input type="date" name="received_date" id="received_date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="expiration_date">Expiration Date</label>
                    <input type="date" name="expiration_date" id="expiration_date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="qty">Quantity</label>
                    <input type="number" name="qty" id="qty" class="form-control" required>
                </div>
                <button class="btn btn-success btn-block">Add Stock</button>
            </form>
        </div>
    </div>
</div>
@endsection