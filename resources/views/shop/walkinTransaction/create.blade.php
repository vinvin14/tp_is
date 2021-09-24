@extends('interface.main')
@section('title','New Transaction')
@section('styles')
    <!-- Custom styles for this page -->
@endsection
@section('main')
    <a href="{{route('transaction.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Walk-in Transactions</a>
    <div class="col-xs-12 col-md-8 col-lg-8">
        <div class="card w-75 shadow-sm mt-2">
            <div class="card-header font-weight-bold text-primary">New Walk-in Transaction</div>
            <div class="card-body">
                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Woops!</strong> {{ Session::get('error')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <form action="{{ route('walkinTransaction.store') }}" method="post">
                    <div class="row">
                        <div class="col-6">
                            <label for="">Transaction Date</label>
                            <input type="date" name="transaction_date" class="form-control" value="{{old('transaction_date')}})" required {{ $errors->first('transaction_date')}}>
                            @error('transaction_date')
                                <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="">Transaction Status</label>
                            <select name="trans_status" id="" class="form-control" required>
                                <option value="pending" {{(old('trans_status') == 'pending') ? 'selected' : ''}}>Pending</option>
                                <option value="completed" {{(old('trans_status') == 'completed') ? 'selected' : ''}}>Completed</option>
                                <option value="unclaimed" {{(old('trans_status') == 'unclaimed') ? 'selected' : ''}}>Unclaimed</option>
                                <option value="unpaid" {{(old('trans_status') == 'unpaid') ? 'selected' : ''}}>Unpaid</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <label for="">Claim Type</label>
                            <select name="claim_type" class="form-control" id="">
                                <option value="">-</option>
                                @foreach ($claimTypes as $claimType)
                                    <option value="{{ $claimType->id }}">{{ $claimType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-6">
                            <label for="">Payment Method</label>
                            <select name="payment_method_id" id="" class="form-control" required>
                                <option value="">-</option>
                                @foreach( $paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" id="" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary form-control mt-3">Add Transaction</button>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

@endsection
