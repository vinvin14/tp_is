@extends('interface.main')
@section('title','Customer Information')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    <a href="{{route('customer.list')}}" class="font-weight-bold"><i class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Customer List</a>
    <div class="card shadow-sm mt-2">
        <div class="card-header font-weight-bold text-primary">
            <i class="fas fa-fw fa-user-tie"></i>Customer Details
        </div>
        <div class="card-body">
            @if(Session::has('response'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong>  {{ Session::get('response')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Woops!</strong>  {{ Session::get('error')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="row">
                <div class="col-4">
                    <label for="" class="">First Name</label>
                    <div class="font-weight-bold">{{ $customer->firstname }}</div>
                </div>
                <div class="col-4">
                    <label for="" class="">Middle Name</label>
                    <div class="font-weight-bold">{{ $customer->middlename }}</div>
                </div>
                <div class="col-4">
                    <label for="" class="">Last Name</label>
                    <div class="font-weight-bold">{{ $customer->lastname }}</div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-4">
                    <label for="">Date of Birth</label>
                    <div class="font-weight-bold">{{ $customer->date_of_birth }}</div>
                </div>
                <div class="col-4">
                    <label for="">Customer Type</label>
                    <div class="font-weight-bold">{{ $customer->customer_type }}</div>
                </div>
                <div class="col-4">
                    <label for="">Total Points</label>
                    <div class="font-weight-bold">{{ $customer->total_points }}</div>
                </div>
            </div>
            <hr>
            <div>
                <label for="">Address</label>
                <div class="font-weight-bold">{{ $customer->address }}</div>
            </div>
            <a href="{{route('customer.update', $customer->id)}}" class="btn btn-secondary my-3 float-right"><i class="fas fa-fw fa-pencil-alt"></i> Update Information</a>
        </div>
    </div>
    <hr>
    <div class="card shadow-sm">
        <div class="card-header font-weight-bold text-primary">
            <i class="fas fa-fw fa-tasks"></i>List of Transactions
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Order Ticket</th>
                        <th>Transaction Date</th>
                        <th>Total Amount</th>
                        <th>Transaction Status</th>
                        <th>Claim Type</th>
                        <th>Payment Type</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <td>{{$transaction->order_ticket}}</td>
                        <td>{{$transaction->transaction_date}}</td>
                        <td>{{$transaction->total_amount}}</td>
                        <td>{{$transaction->trans_status}}</td>
                        <td>{{$transaction->claim_type}}</td>
                        <td>{{$transaction->payment_type}}</td>
                        <td><a href="{{route('transaction.show', $transaction->id)}}"><i class="fas fa-fw fa-eye" title="View"></i></a></td>
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
    <script src="{{ asset('includes/sbadmin/vendor/jquery/jquery.slim.min.js') }}"></script>
    <script src="{{ asset('includes/sbadmin/js/demo/datatables-demo.js') }}"></script>

@endsection
