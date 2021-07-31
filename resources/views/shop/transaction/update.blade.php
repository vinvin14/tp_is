@extends('interface.main')
@section('title','Transaction')
@section('styles')
    <!-- Custom styles for this page -->
@endsection
@section('main')
    <div class="card shadow-sm col-lg-8 col-xs-12 col-md-6">
        <div class="card-header">
            Update Transaction
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <small>Customer</small>
                    <div class="font-weight-bold">{{ $transaction->customer_name }}</div>
                </div>
                <div class="col-3">
                    <small>Order Ticket</small>
                    <div class="font-weight-bold">{{ $transaction->order_ticket }}</div>
                </div>
                <div class="col-3">
                    <small>Claim Type</small>
                    <select name="claim_type" id="" class="form-control">
                        <option value="">-</option>
                        @foreach ($claim_types as $claim_type)
                            <option value="{{ $claim_type->id }}">{{ $claim_type->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <small>Payment Type</small>
                    <select name="payment_type" id="" class="form-control">
                        <option value="">-</option>
                        @foreach ($payment_types as $payment_type)
                            <option value="{{ $payment_type->id }}">{{ $payment_type->type_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection
