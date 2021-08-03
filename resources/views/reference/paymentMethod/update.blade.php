@extends('interface.main')
@section('title','Payment Method Update')
@section('styles')
    <!-- Custom styles for this page -->

@endsection
@section('main')
    <a href="{{route('paymentMethod.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Unit List</a>
    <div class="col-xs-12 col-lg-4">
        <div class="card shadow-sm mt-2">
            <div class="card-header font-weight-bold text-primary">
                New Product Unit
            </div>
            <div class="card-body">
                @include('interface.system-messages')
                <form action="{{ route('paymentMethod.update', $paymentMethod->id) }}" method="post" role="form">
                    @csrf
                    <div class="form-group">
                        <label for="name">Payment Method</label>
                        <input type="text" name="name" class="form-control" value="{{ $paymentMethod->name }}" placeholder="Payment Method here" required>
                    </div>
                    <button type="submit" class="btn btn-success float-right"><i class="fas fa-fw fa-save"></i>Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
