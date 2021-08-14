@extends('interface.main')
@section('title','Transaction')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    <a href="{{route('transaction.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Transaction List</a>
    @include('system-message')
    <div class="card mt-2">
        <div class="card-body">
            <h5 class="font-weight-bold">Current Transaction</h5>
            <hr>
            <a href="{{ route('transaction.update', $transaction->transaction_id) }}"
                class="btn btn-secondary">
                 <i class="fas fa-fw fa-pencil-alt"></i> Update this Transaction</a>
            <div class="row">
                <div class="col-3">
                    <small class="font-weight-bold">Customer</small>
                    <div>{{ $transaction->lastname   }}
                        {{ $transaction->firstname }} {{ $transaction->middlename }}</div>
                </div>
                <div class="col-3">
                    <small class="font-weight-bold">Ticket Number</small>
                    <div>{{ $transaction->ticket_number }}</div>
                </div>
                <div class="col-3">
                    <small class="font-weight-bold">Claim Type</small>
                    <div>{{ $transaction->claim_type }}</div>
                </div>
                <div class="col-3">
                    <small class="font-weight-bold">Payment Method</small>
                    <div>{{ $transaction->payment_method }}</div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-3">
                    <small class="font-weight-bold">Total Amount</small>
                    <div>₱{{ $transaction->total_amount   }}</div>
                </div>
                <div class="col-3">
                    <small class="font-weight-bold">Total Points</small>
                    <div>{{ $transaction->total_amount   }}</div>
                </div>
                <div class="col-3">
                    <small class="font-weight-bold">Transaction Status</small>
                    <div>{{ $transaction->trans_status   }}</div>
                </div>
                <div class="col-3">
                    <small class="font-weight-bold">Transaction Date</small>
                    <div>{{ $transaction->transaction_date }}</div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="card">
        <div class="card-body">
            <h5>List of Existing Orders within this Transaction</h5>
            @if($transaction->trans_status == 'pending')
                <a href="{{ route('order.create', $transaction->transaction_id) }}" class="btn btn-primary">
                    <i class="fas fa-fw fa-plus"></i> Add Product</a>
            @endif

            <hr>
            {{-- <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Product</th>
                        <th>Product Category</th>
                        <th>Unit</th>
                        <th>Points</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <div class="text-center">
                                    <a href="{{route('order.update', $order->id)}}" class="pr-2"><i
                                            class="fas fa-fw fa-pencil-alt" title="Update"></i></a>
                                    <a href="{{route('order.destroy', $order->id)}}" class="pl-2"
                                       onclick="return confirm('Are you sure you want to delete this order?')"><i
                                            class="fas fa-fw fa-trash text-danger" title="Delete"></i></a>
                                </div>
                            </td>
                            <td>{{$order->item_title}}</td>
                            <td>{{$order->category_name}}</td>
                            <td>{{$order->unit}}</td>
                            <td>{{$order->points}}</td>
                            <td>{{$order->quantity}}</td>
                            <td>₱ {{$order->price}}</td>
                            <td>₱ {{$order->total_order_amount}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfooter>
                        <tr>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th>Total Order Amount</th>
                            <th>₱</th>
                        </tr>
                    </tfooter>
                </table>
            </div> --}}
        </div>
    </div>
    @if($transaction->trans_status == 'pending')
                <a href="{{ route('transaction.checkout', $transaction->transaction_id) }}" class="btn btn-danger btn-block shadow-sm mt-3">
                    <i class="fas fa-fw fa-money-bill"></i> Proceed to Checkout</a>
            @endif

@endsection
@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('includes/sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('includes/sbadmin/js/demo/datatables-demo.js') }}"></script>

@endsection
