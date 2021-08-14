@extends('interface.main')
@section('title','Transaction')
@section('styles')
    <!-- Custom styles for this page -->
    <style>
        h3, h5{
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }

    </style>
@endsection
@section('main')
    <div class="container text-center">
        <h3 class="text-center">List of Available Products</h3>
        <hr>
        <a href="{{ route('order.create', $transaction_id) }}" class="btn btn-primary mr-2">All</a>
        @forelse ($categories as $category)
            <a href="{{ route('order.create', $transaction_id) }}?category={{ $category->id }}" class="btn btn-primary mr-2">{{ $category->name }}</a>
        @empty

        @endforelse
        <div class="row my-3">
            @forelse ($products as $product)
            <div class="col-xs-12 col-md-6 col-lg-3 my-3">
                <div class="card shadow-sm" style="max-width: 17rem;">
                    @if (empty($product->uploaded_img))
                        <img class="card-img-top" src="{{ asset('storage/utilities/no_image.png') }}"  height="200px"   alt="Card image cap">
                    @else
                        <img class="card-img-top" src="{{ $product->uploaded_img }}"  height="200px"  alt="Card image cap">
                    @endif
                    <div class="card-body">
                      <h6 class="card-title font-weight-bold text-truncate text-center" style="cursor: pointer" title="{{ $product->title }}">{{ $product->title }}</h6>
                      <div class="text-center">
                        <div class="border-bottom">
                            @empty($product->qty)
                                <small class="font-weight-bold">No Stock Yet</small>
                            @else
                                <small class="font-weight-bold">Quantity: {{ $product->qty }} {{ $product->unit }}</small>
                            @endempty
                        </div>
                        <a href="#" class="btn btn-primary mt-3">Add this Product</a>
                      </div>
                    </div>
                  </div>
            </div>
        @empty
            <div class="col-12 my-3">
                <h3>No Record(s) Found</h3>
            </div>
        @endforelse
        </div>
    </div>
@endsection
