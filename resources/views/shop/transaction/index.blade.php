@extends('interface.main')
@section('title','Transaction List')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    <h5 class="border-bottom">Existing Transactions</h5>
    <p class="font-italic">Here are all existing transactions!</p>
    <a href="{{route('transaction.create')}}" class="btn btn-primary my-3 shadow-sm"><i class="fas fa-fw fa-plus"></i> Add
        New Transaction</a>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-handshake"></i> Transactions Table</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Ticket #</th>
                        <th>Transaction Date</th>
                        <th>Total Amount</th>
                        <th>Total Points</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{$transaction->firstname.' '.$transaction->middlename.' '.$transaction->lastname}}</td>
                            <td>{{$transaction->ticket_number}}</td>
                            <td>{{$transaction->transaction_date}}</td>
                            <td>₱{{number_format($transaction->total_amount)}}</td>
                            <td>{{$transaction->total_points}}</td>
                            <td>{{$transaction->trans_status}}</td>
                            <td>
                                <div class="text-center">
                                    <a href="{{route('transaction.show', $transaction->transaction_id)}}" class="pr-2"><i class="fas fa-fw fa-eye" title="View"></i></a>
                                    <a href="{{route('transaction.destroy', $transaction->transaction_id)}}" class="pl-2" onclick="return confirm('Are you sure you want to delete this specification?')"><i class="fas fa-fw fa-trash text-danger" title="Delete"></i></a>
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
