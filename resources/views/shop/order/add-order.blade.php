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
        <a href="{{ route('order.add', $transaction_id) }}" class="btn btn-primary mr-2">All</a>
        @forelse ($categories as $category)
            <a href="{{ route('order.add', $transaction_id) }}?category={{ $category->id }}" class="btn btn-primary mr-2">{{ $category->name }}</a>
        @empty

        @endforelse
        <div class="row my-3">
            @forelse ($products as $product)
            <div class="col-xs-12 col-md-6 col-lg-3 my-3" id='product'>
                <div class="card shadow-sm" style="max-width: 17rem;">
                    @if (empty($product->uploaded_img))
                        <img class="card-img-top" src="{{ asset('storage/utilities/no_image.png') }}" height="180px" alt="Card image cap" id="product">
                    @else
                        <img class="card-img-top" src="{{ $product->uploaded_img }}" height="180px" alt="Card image cap" id="product">
                    @endif
                    <form action="">
                        <div class="card-body">
                        <h6 class="card-title font-weight-bold text-truncate text-center" style="cursor: pointer" title="{{ $product->title }}">{{ $product->title }}</h6>
                        <div class="text-center">
                            <div class="border-bottom">
                                @empty($product->qty)
                                    <div class="">₱{{ $product->price }}</div>
                                    <small class="font-weight-bold">No Stock Yet</small>
                                @else
                                    <div class="">₱{{ $product->price }}</div>
                                    <small class="font-weight-bold" >Quantity: <span id="product-qty">{{ $product->qty }}</span> {{ $product->unit }}</small>
                                @endempty
                            </div>
                            <a href="#" class="btn btn-primary mt-3" onclick="return confirm('Are you sure you want to add this Product?')">Add this Product</a>
                        </div>
                        </div>
                    </form>
                  </div>
            </div>
            
        @empty
            <div class="col-12 my-3">
                <h3>No Record(s) Found</h3>
            </div>
        @endforelse
        </div>
        <div class="m-2">
            {{ $products->links()}}
        </div>
        
    </div>
@endsection
{{-- @section('scripts')
    <script>
        $(document).ready(function (){
            $('img[id="product"]').click(function () {
                $(this).toggleClass('img-selected');
                $(this).siblings().find('.card-body').toggleClass('border border-primary');
                $(this).siblings().find('#order-qty').toggle();
            });
        });
    </script>
@endsection --}}