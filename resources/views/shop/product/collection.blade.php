@extends('interface.main')
@section('title','ProductOld')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    <a href="{{route('product.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Product List</a>

    <div class="mt-4">
        <h5 class="border-bottom">Product Collection</h5>
        <p class="font-italic">Here are all Product Collection of {{ $specification->item_title }}!</p>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="text-primary font-weight-bold">{{ $specification->item_title }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="{{ asset($specification->uploaded_img) }}" class="p-2 border shadow" width="300px" height="300px" alt="">
                    <div class="font-weight-bold border-bottom my-2">Product Details</div>

                    <div class="row">
                        <div class="col-4 border-right">
                            <div class="text-info">Total Quantity </div>
                        </div>
                        <div class="col-8">
                            {{$productsCount. ' '. $specification->unit}}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-4 border-right">
                            <div class="text-info">Category </div>
                        </div>
                        <div class="col-8">
                            {{$specification->category_name}}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-4 border-right">
                            <div class="text-info">Unit </div>
                        </div>
                        <div class="col-8">
                            {{$specification->unit}}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-4 border-right">
                            <div class="text-info">Price </div>
                        </div>
                        <div class="col-8">
                            ₱{{$specification->price}}
                        </div>
                    </div>

                </div>
                <div class="col-md-8">
                    <div class="table-responsive">
                        <div class="font-weight-bold">List of Current Products</div>
                        <a href="{{ route('product.create') }}" class="btn btn-primary m-2"><i class="fas fa-fw fa-shopping-bag"></i> Add New</a>
                        @if(Session::has('response'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong>  {{ Session::get('response')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <hr>
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Expiry Date</th>
                                <th>Received Date</th>
                                <th>Initial Quantity</th>
                                <th>Current Quantity</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{$product->expiry_date}}</td>
                                    <td>{{$product->date_received}}</td>
                                    <td>{{$product->initial_quantity}}</td>
                                    <td>{{$product->current_quantity}}</td>
                                    <td>
                                        <div class="text-center">
                                            <a href="{{route('product.update', $product->id)}}" class="pr-2"><i class="fas fa-fw fa-pencil-alt" title="Edit"></i></a>
                                            <a href="{{route('product.destroy', $product->id)}}" class="pl-2" onclick="return confirm('Are you sure you want to delete this ProductOld?')"><i class="fas fa-fw fa-trash text-danger" title="Delete"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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
