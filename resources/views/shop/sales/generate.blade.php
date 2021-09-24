@extends('interface.main')
@section('title','Sales Statistics')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
{{-- {{dd($total_sale)}} --}}
<a href="{{route('sales.home')}}" class="font-weight-bold"><i
    class="fas fa-fw fa-arrow-alt-circle-left"></i> Go Back</a>
<h3>Sale Statistics for the date of {{$date_range[0]}} to {{$date_range[1]}}</h3>
<div class="row">
    <div class="col-lg-6 col-md-6 col-xs-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="font-weight-bold">Total Sold Items</div>
                @if (!empty($total_sale->totalQty))
                <div class="h1 font-weight-bold">{{$total_sale->totalQty}}</div>
                @else
                <div class="h4 font-weight-bold">No Record(s) Found!</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-xs-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="font-weight-bold">Total Sale</div>
                @if (!empty($total_sale->finalAmount))
                <div class="h1 font-weight-bold">₱ {{$total_sale->finalAmount}}</div>
                @else
                <div class="h4 font-weight-bold">No Record(s) Found!</div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm mt-5">
    <div class="card-header">
        List of Total Sold Products
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Total Sold</th>
                    <th>Total Amount Sold</th>
                    {{-- <th>Price</th>
                    <th>Points</th>
                    <th>Alert Level</th>
                    <th>Actions</th> --}}
                </tr>
                </thead>
                <tbody>
                @foreach($sold_products as $soldProduct)
                    <tr>
                       <td>{{$soldProduct->title}}</td>
                       <td>₱ {{$soldProduct->price}}</td>
                       <td>{{$soldProduct->totalSold}} {{$soldProduct->unit}}</td>
                       <td>₱ {{$soldProduct->totalFinalAmount}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('includes/sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('includes/sbadmin/js/demo/datatables-demo.js') }}"></script>

@endsection