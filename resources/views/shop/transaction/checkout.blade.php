@extends('interface.main')
@section('title','Transaction')
@section('styles')
    <!-- Custom styles for this page -->
    <style>
        .row li {
            width: 33.3%;
            float: left;
        }

        img {
            border: 0 none;
            display: inline-block;
            max-width: 100%;
            vertical-align: middle;
            height: 120px;
            width: 150px;
        }
    </style>
@endsection
@section('main')
    <div class="container">
        <a href="{{route('transaction.show', $transaction->transaction_id)}}" class="font-weight-bold"><i
                class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Current Transaction</a>
        <div class="lead mt-4">
            Current Customer: <span
                class="font-weight-bold">{{ $transaction->firstname }} {{ $transaction->middlename }} {{ $transaction->lastname }}</span>
        </div>
        {{--<form action="{{ route('transaction.checkout') }}" method="post">--}}
            @csrf
        <div class="row">
            <div class="col-6 text-left">
                <h4 class="font-weight-bold">List of Orders</h4>
            </div>
            <div class="col-6 text-right">
                <div class="form-group">
                    <label for="payment_type">Payment Type</label>
                    <select name="payment_type" id="payment_type">
                        <option value="">-</option>
                        @foreach ($paymentTypes as $paymentType)
                            <option value="{{ $paymentType['id'] }}"
                            @if($transaction-> payment_type_id == $paymentType['id'])
                                selected
                            @endif
                            >{{ $paymentType['type_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <hr>
        @if(empty($orders->toArray()))
            <div class="text-center font-weigh-bold">No Products Ordered</div>
        @else
                @foreach ($orders as $order)
                    <div class="card shadow-sm my-3">
                        <div class="card-body">
                            <div class="row justify-content-start">
                                <div class="col-sm-2 text-center">
                                    <div class="inline-block">
                                        <img src="{{ asset($order->uploaded_img) }}" alt="">

                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <h4 class="font-weight-bold border-bottom">{{ $order->item_title }}</h4>
                                    <div class="row">
                                        <div class="col-6">
                                            <div>Quantity: <span class="font-weight-bold"> {{ $order->quantity }}x</span></div>
                                            <div>Unit: <span class="font-weight-bold"> {{ $order->unit }}</span></div>
                                            <div>Product Category: <span
                                                    class="font-weight-bold">{{ $order->category_name }}</span></div>
                                            <div>Price: <span class="font-weight-bold"> ₱ {{ $order->price }}</span></div>
                                        </div>
                                        <div class="col-6 text-right pr-5">
                                            Total
                                            <h3 class="font-weight-bold">₱ {{ $order->total_order_amount }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="footer pl-1 mt-3">
                                <a href="{{ route('order.destroy', $order->id) }}"
                                   onclick="return confirm('Are you sure you want to delete this order?')"
                                   class="btn btn-danger">Remove Order</a>
                                <a href="" class="btn btn-primary ml-2">Apply Discount</a>
                            </div>
                        </div>
                    </div>
                @endforeach
        @endif
        <hr>
        <div class="float-right mb-2">
            <h4 class="font-weight-bold pr-3">Amount to be Paid: ₱{{$orderTotalAmount}}</h4>
        </div>
        <button class="btn btn-outline-danger font-weight-bold float-right form-control mb-3">Checkout and Finalize
            Transaction
        </button>
        </form>
    </div>

@endsection
