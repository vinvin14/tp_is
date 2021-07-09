@extends('interface.main')
@section('title','ProductOld')
@section('styles')
@endsection
@section('main')
    <a href="{{route('product.show.collection', $product->specifications_id)}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Product Collection</a>
    <div class="w-75">
        <div class="card shadow-sm mt-3">
            <div class="card-header font-weight-bold text-primary">
                <i class="fas fa-fw fa-shopping-bag"></i> Product Details
            </div>
            <div class="card-body">
                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Woops!</strong>  {{ Session::get('error')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <h5 class="font-weight-bold">Update details for {{ $product->item_title }}</h5>
                <form action="{{ route('product.upsave', $product->id) }}" method="post">
                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="">Expiration Date</label>
                            <input type="date" name="expiry_date" value="{{ $product->expiry_date }}" class="form-control">
                        </div>
                        <div class="col-6">
                            <label for="">Received Date</label>
                            <input type="date" name="date_received" value="{{ $product->date_received }}" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="">Inital Quantity</label>
                            <input type="number" name="initial_quantity" value="{{ $product->initial_quantity }}" class="form-control">
                        </div>
                        <div class="col-6">
                            <label for="">Current Quantity</label>
                            <input type="number" name="current_quantity" value="{{ $product->current_quantity }}" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success form-control mt-4">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection

