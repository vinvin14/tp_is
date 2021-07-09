@extends('interface.main')
@section('title','Specification List')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    <a href="{{route('specification.create')}}" class="btn btn-primary"><i class="fas fa-fw fa-user-plus"></i> Add
        New Specifications</a>

    <div class="mt-4">
        <h5 class="border-bottom">Product Specifications</h5>
        <p class="font-italic">Here are all Product specifications that are ready to be used!</p>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-users"></i> Product Specification Table</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Product Title</th>
                        <th>Product Category</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Points</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($specifications as $specification)
                        <tr>
                            <td>{{$specification->item_title}}</td>
                            <td>{{$specification->category_name}}</td>
                            <td>{{$specification->unit}}</td>
                            <td>₱ {{$specification->price}}</td>
                            <td>{{$specification->points}}</td>
                            <td>
                                <div class="text-center">
                                    <a href="{{route('specification.show', $specification->id)}}" class="pr-2"><i class="fas fa-fw fa-eye" title="View"></i></a>
                                    <a href="{{route('specification.destroy', $specification->id)}}" class="pl-2" onclick="return confirm('Are you sure you want to delete this specification?')"><i class="fas fa-fw fa-trash text-danger" title="Delete"></i></a>
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
