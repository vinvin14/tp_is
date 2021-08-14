@extends('interface.main')
@section('title','Product List')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    <div class="mt-4">
        <h5 class="border-bottom">Existing Products</h5>
        <p class="font-italic">Here are all existing Products that can be added to transaction!</p>
    </div>
    <a href="{{ route('product.create') }}" class="btn btn-primary my-3 px-4 shadow-sm"><i class="fas fa-fw fa-plus"></i> New Product</a>
    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-shopping-bag"></i> Products Table</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Points</th>
                        <th>Alert Level</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                @if ($product->uploaded_img == null)
                                    <div class="text-center">
                                        <small>No Image to Preview</small>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <img src="{{ asset($product->uploaded_img) }}" width="80px" height="80px" alt="">
                                    </div>
                                @endif
                            </td>
                            <td class="py-4">{{ $product->title }}</td>
                            <td class="py-4">{{ $product->category }}</td>
                            <td class="py-4">
                                @if ($product->qty == null)
                                    <small>No stocks yet</small>
                                @else
                                {{ $product->qty }} {{ $product->unit }}
                                @endif
                            </td>
                            <td class="py-4">₱{{ $product->price }}</td>
                            <td class="py-4">{{ $product->points }}</td>
                            <td class="py-4">{{ $product->alert_level }}</td>
                            <td class="py-4">
                                <div class="text-center">
                                    <a href="{{route('product.show', $product->id)}}" class="pr-2"><i class="fas fa-fw fa-eye" title="View"></i></a>
                                    <a href="{{route('product.destroy', $product->id)}}" class="pl-2" onclick="return confirm('Are you sure you want to delete this product?')"><i class="fas fa-fw fa-trash text-danger" title="Delete"></i></a>
                                </div>
                            </td>
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