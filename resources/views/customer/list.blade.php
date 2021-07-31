@extends('interface.main')
@section('title','Customer List')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    <a href="{{route('customer.create')}}" class="btn btn-primary"><i class="fas fa-fw fa-user-plus"></i> Add
        Customer</a>

    <div class="mt-4">
        <h5 class="border-bottom">Customers</h5>
        <p class="font-italic">Here are all customers that have or had transaction(s) with us!</p>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-users"></i> Customer Table</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Address</th>
                        <th>Customer Type</th>
                        <th>Points</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>{{$customer->firstname}}</td>
                            <td>{{$customer->middlename}}</td>
                            <td>{{$customer->lastname}}</td>
                            <td><textarea name="" id="" class="form-control" cols="30" rows="1"
                                          readonly>{{$customer->address}}</textarea></td>
                            <td>{{$customer->customer_type}}</td>
                            <td>{{$customer->current_points}}</td>
                            <td><a href="{{route('customer.show', $customer->id)}}"><i class="fas fa-fw fa-eye" title="View"></i></a></td>
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
