@extends('interface.main')
@section('title','Transaction')
@section('styles')
    <!-- Custom styles for this page -->
@endsection
@section('main')
    <a href="{{route('transaction.show', $transaction->id)}}" class="font-weight-bold"><i
        class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Current Transaction</a>
    <div class="col-lg-6 col-md-6 col-xs-12 mt-2">
        <div class="card shadow-sm">
            <div class="card-header text-primary font-weight-bold">
                <i class="fas fa-pencil-alt"></i> Update Transaction
            </div>
            <div class="card-body">
                <form action="{{route('transaction.upsave', $transaction->id)}}" method="post">
                    <div class="form-group">
                        <label for="" class="font-weight-bold">Customer</label>
                        <input type="text" class="form-control" value="{{$transaction->lastname.', '.$transaction->firstname.' '.$transaction->middlename}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="" class="font-weight-bold">Ticket #</label>
                        <input type="text" class="form-control" value="{{$transaction->ticket_number}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="" class="font-weight-bold">Transaction Date</label>
                        <input type="date" name="transaction_date" value="{{$transaction->transaction_date}}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="" class="font-weight-bold">Payment Method</label>
                        <select name="payment_method_id" class="form-control" required>
                            @foreach ($paymentMethods as $paymentMethod)
                                <option value="{{$paymentMethod->id}}"
                                @if ($transaction->payment_method_id == $paymentMethod->id)
                                    selected
                                @endif
                                >{{$paymentMethod->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="font-weight-bold">Claim Type</label>
                        <select name="claim_type" id="" class="form-control">
                            @foreach ($claimTypes as $claimType)
                                <option value="{{$claimType->id}}"
                                @if ($transaction->id = $claimType->id)
                                    selected
                                @endif
                                >{{$claimType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Remarks</label>
                        <textarea name="remarks" id="" cols="30" rows="10" class="form-control">{{$transaction->remarks}}</textarea>
                    </div>
                    <button type="submit" class="form-control btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
