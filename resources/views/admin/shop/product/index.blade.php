@extends('interface.main')
@section('title','Products')
@section('styles')
    <style>
    </style>
@endsection
@section('main')
    <div class="container-fluid">

        <h4 class="text-center font-weight-bold">Existing Products <br><a href="{{ route('product.create') }}" class="btn btn-primary m-2"><i class="fas fa-fw fa-shopping-bag"></i> Add Product</a></h4>
        <hr>
        <div class="album">
            <div class="container-fluid">
                <div class="row">
                    @foreach( $products as $product)
                        <div class="col-md-3">
                            <div class="card my-3 box-shadow shadow">
                                <img class="card-img-top" src="{{asset( $product->uploaded_img )}}" width="200px"
                                     height="200px" alt="">
                                <div class="card-body text-center">
                                    <div class="card-text text-truncate font-weight-bold">{{ $product->item_title }}</div>
                                    <div class="font-italic font-weight-bold">₱ {{ number_format($product->price) }}</div>
                                    <div class="font-italic text-truncate @if($product->current_quantity < $product->minimum_quantity) text-danger @endif">Quantity: {{ $product->current_quantity.' '.$product->unit }}</div>
                                    <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary form-control m-1"><i class="fas fa-fw fa-eye"></i> View Product</a>
                                    <a href="{{ route('product.show', $product->id) }}" class="btn btn-danger form-control m-1"><i class="fas fa-fw fa-cart-arrow-down"></i> Add to Order</a>
                                </div>
                            </div>
                        </div>
                        {{--<div class="col-xs-6 col-md-4 col-lg-3 overflow-hidden">--}}
                        {{--<div class="col-xs-6 col-md-4 col-lg-3  overflow-hidden py-3 ">--}}
                        {{--<div class="container">--}}
                        {{--<img class="border p-1 shadow-sm" src="{{asset( $product->uploaded_img )}}" width="200px"--}}
                        {{--height="200px" alt="">--}}
                        {{--<div class="text-center">--}}
                        {{--<div class="text-truncate">{{ $product->item_title }}</div>--}}
                        {{--<div class="font-italic font-weight-bold">₱ {{ $product->price }}</div>--}}
                        {{--<small class="font-italic">Quantity: {{ $product->total_quantity.' '.$product->unit }}</small>--}}
                        {{--<a href="{{ route('product.show.collection', $product->specifications_id) }}" class="btn btn-primary">View ProductOld</a>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
