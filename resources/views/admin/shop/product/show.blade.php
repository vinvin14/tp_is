@extends('interface.main')
@section('title','Product Details')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    <a href="{{route('product.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Product List</a>
    <div class="card mt-2 shadow-sm">
        <div class="card-header">
            <h3 class="font-weight-bold">{{ $product->item_title }}</h3>
        </div>
        <div class="card-body">
            @include('system-message')
            <div class="p-2">
                <a href="{{ route('product.quantity.add', $product->id) }}" class="btn btn-primary mr-2"><i class="fas fa-fw fa-plus mb-2"></i>Add Quantity</a>
                <a href="{{ route('product.update', $product->id) }}" class="btn btn-warning mr-2"><i class="fas fa-fw fa-pencil-alt mb-2"></i>Update</a>
                <a href="{{ route('product.destroy', $product->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Product?')"><i class="fas fa-fw fa-trash mb-2"></i>Delete this Record</a>
            </div>
            <div class="row m-3">
                <div class="col-md-3 border-right">
                    <div class="font-weight-bold text-muted py-2">Product Image</div>
                    <img src="{{ asset($product->uploaded_img) }}" class="p-2 border shadow" width="250px" height="250px" alt="">
                </div>
                <div class="col-md-9">
                    <div class="font-weight-bold text-muted py-2">Product Details</div>
                    <div class="row mt-2">
                        <div class="col-4">
                            <small>Product Category</small>
                            <div class="font-weight-bold">{{ $product->category_name }}</div>
                        </div>
                        <div class="col-4">
                            <small>Product Price</small>
                            <div class="font-weight-bold">₱{{ $product->price }}</div>
                        </div>
                        <div class="col-4">
                            <small>Purchase Points</small>
                            <div class="font-weight-bold">{{ $product->points }} point(s)</div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-4">
                            <small>Minimum Quantity</small>
                            <div class="font-weight-bold">{{ $product->minimum_quantity }}</div>
                        </div>
                        <div class="col-4">
                            <small>Current Quantity</small>
                            <div class="font-weight-bold">{{ $product->current_quantity }}</div>
                        </div>
                        <div class="col-4">
                            <small>Product Unit</small>
                            <div class="font-weight-bold">{{ $product->unit }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="my-3">
                        <div class="font-weight-bold mb-2">Current Product Quantity Record</div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Received Date</th>
                                    <th>Expiration Date</th>
                                    <th>Quantity</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($productQuantity as $row)
                                    <tr>
                                        <td>{{ $row->received_date }}</td>
                                        <td>{{ $row->expiration_date }}</td>
                                        <td>{{ $row->quantity }}</td>
                                        <td>
                                            <div class="text-center">
                                                <a href="{{route('product.quantity.update', $row->id)}}" class="pr-2"><i class="fas fa-fw fa-pencil-alt" title="Edit"></i></a>
                                                <a href="{{route('product.quantity.destroy', $row->id)}}" class="pl-2" onclick="return confirm('Are you sure you want to delete this unit?')"><i class="fas fa-fw fa-trash text-danger" title="Delete"></i></a>
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
    </div>
@endsection
@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('includes/sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('includes/sbadmin/js/demo/datatables-demo.js') }}"></script>

@endsection
