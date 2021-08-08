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
            <h3 class="font-weight-bold">{{ $product->title }}</h3>
        </div>
        <div class="card-body">
            @include('interface.system-messages')
            <div class="p-2">
                <a href="{{ route('stock.create', $product->id) }}" class="btn btn-primary mr-2"><i class="fas fa-fw fa-plus mb-2"></i>Add Stocks</a>
                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning mr-2"><i class="fas fa-fw fa-pencil-alt mb-2"></i>Update</a>
                <a href="{{ route('product.destroy', $product->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Product?')"><i class="fas fa-fw fa-trash mb-2"></i>Delete this Record</a>
            </div>
            <div class="row m-3">
                <div class="col-xs-12 col-md-6 col-lg-3 border-right text-center">
                    <div class="h3 font-weight-bold text-muted py-2">Product Image</div>
                    @if ($product->uploaded_img == null)
                        <div class="font-weight-bold">No Image Available</div>
                    @else    
                    <img src="{{ asset($product->uploaded_img) }}" class="p-2 border shadow" width="250px" height="250px" alt="">
                    @endif
                </div>
                <div class="col-xs-12 col-md-6 col-lg-9">
                    <div class="h3 font-weight-bold text-muted py-2">Product Details</div>
                    <div class="row mt-2">
                        <div class="col-4">
                            <small>Product Category</small>
                            <div class="font-weight-bold">{{ $product->category }}</div>
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
                            <small>Alert Level Quantity</small>
                            <div class="font-weight-bold">{{ $product->alert_level }}</div>
                        </div>
                        <div class="col-4">
                            <small>Product Unit</small>
                            <div class="font-weight-bold">{{ $product->unit }}</div>
                        </div>
                        <div class="col-4">
                            <small>Current Quantity</small>
                            <div class="font-weight-bold">{{ $product->qty }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="my-3">
                        <div class="h3 font-weight-bold mb-2">Current Stocks</div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Received Date</th>
                                    <th>Expiration Date</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($stocks as $row)
                                    <tr 
                                    @if ($row->expiration_date < \Carbon\Carbon::now())
                                        class="text-danger font-weight-bold" data-toggle="tooltip" data-placement="top" title="This stock has expired!"
                                    @endif    
                                    >
                                        <td>
                                            @if ($row->expiration_date < \Carbon\Carbon::now())
                                            <i class="fas fa-exclamation-triangle"></i>
                                            @endif
                                            {{ $row->received_date }}
                                        </td>
                                        <td>
                                            @if ($row->expiration_date < \Carbon\Carbon::now())
                                            <i class="fas fa-exclamation-triangle"></i>
                                            @endif
                                            {{ $row->expiration_date }}
                                        </td>
                                        <td>
                                            @if ($row->expiration_date < \Carbon\Carbon::now())
                                            <i class="fas fa-exclamation-triangle"></i>
                                            @endif
                                            {{ $row->qty }}
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <a href="{{route('stock.edit', $row->id)}}" class="pr-2"><i class="fas fa-fw fa-pencil-alt" title="Edit"></i></a>
                                                @if ($row->expiration_date < \Carbon\Carbon::now())
                                                <a href="{{route('stock.edit', $row->id)}}" class="pr-2"><i class="fas fa-times text-warning" title="discard"></i></a>
                                                @endif
                                                <a href="{{route('stock.destroy', [$row->id, $row->product_id])}}" class="pl-2" onclick="return confirm('Are you sure you want to delete this stock?')"><i class="fas fa-fw fa-trash text-danger" title="Delete"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No Record(s) Found!</td>
                                    </tr>
                                @endforelse
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
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
    <!-- Page level plugins -->
    <script src="{{ asset('includes/sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('includes/sbadmin/js/demo/datatables-demo.js') }}"></script>

@endsection
