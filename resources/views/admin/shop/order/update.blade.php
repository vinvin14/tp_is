@extends('interface.main')
@section('title','Update Order')
@section('styles')
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endsection
@section('main')
    <a href="{{route('transaction.show', $transaction_id)}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Current Transaction</a>
    <div class="card w-50 shadow-sm mt-2">
        <div class="card-header">
            <h6 class="font-weight-bold text-primary"><i class="fas fa-fw fa-shopping-cart"></i> Add New Order</h6>
        </div>
        <div class="card-body">
            @include('system-message')
            <form action="{{ route('order.upsave', $order->id) }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="product" class="font-weight-bold">Product</label>
                    <input type="text" name="" value="{{ $product->item_title }}" id="product" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="product" class="font-weight-bold">Quantity</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <a class="btn btn-danger font-weight-bold" id="q-minus"><i class="fas fa-fw fa-minus"></i>
                            </a>
                        </div>
                        <input type="hidden" value="{{ $product->current_quantity + $order->quantity}}" id="product-current-quantity">
                        <input type="number" name="quantity" value="{{ $order->quantity }}" class="form-control text-center" min="1" id="quantity"
                               placeholder="Quantity" autocomplete="off">
                        <div class="input-group-append">
                            <a class="btn btn-success font-weight-bold" id="q-add"><i class="fas fa-fw fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="product" class="font-weight-bold">Price</label>
                            <input type="number" name="" id="price" value="{{ $product->price }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="product" class="font-weight-bold">Total Amount</label>
                            <input type="number" name="total_order_amount" value="{{ $order->total_order_amount }}" id="total-amount" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="product" class="font-weight-bold">Total Points</label>
                            <input type="hidden" id="product-points" value="{{ $product->points }}">
                            <input type="number" name="total_points" value="{{ $order->total_points }}" data-id="" id="total-points" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success form-control mt-2"><i class="fas fa-fw fa-check"></i> Add this Order</button>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            function totalAmount()
            {
                var productPrice = parseInt($('#price').val());
                var productQuantity = parseInt($('#quantity').val());

                $('#total-amount').val(productPrice*productQuantity);
            }
            function totalPoints()
            {
                var productPoints = $('#product-points').val();
                var productQuantity = parseInt($('#quantity').val());

                $('#total-points').val(productPoints*productQuantity);
            }
            $('#q-minus').click(function () {
                if($('#product').val() === '') {
                    alert('There is no product selected yet!');
                    return false;
                }
                var currentQty = $('#quantity').val();
                if (currentQty < 1) {
                    alert('Quantity should have a value!');
                    $('#quantity').val(1);
                    return false;
                } else {
                    $('#quantity').val(currentQty - 1);
                }
                totalAmount();
                totalPoints()
            });
            $('#q-add').click(function () {
                if($('#product').val() === '') {
                    alert('There is no product selected yet!');
                    return false;
                }
                var productCurrentQty = $('#product-current-quantity').val();
                var currentQty = $('#quantity');
                // console.log(parseInt(currentQty.val())+1);
                if(parseInt(productCurrentQty) < parseInt(currentQty.val())+1) {
                    alert('You have reached the Product Quantity limit!');
                    $('#quantity').val(parseInt(currentQty.val()));
                    return false;
                }
                if (currentQty.val() === '') {
                    currentQty.val(1);
                } else {
                    currentQty.val(parseInt(currentQty.val()) + 1).change();
                }
                totalAmount();
                totalPoints()
            });

            $('#product').click(function () {
                $('#product-modal').modal('show');
                $('div[id="product-option"]').click(function () {
                    var productID = $(this).data('id');
                    var productTitle = $(this).find('#product-title').text();
                    var productPrice = $(this).find('#product-price').text();
                    var productPoints = $(this).find('#product-points').text();
                    var productQuantity = $(this).find('#product-quantity').text();

                    $('#product').val(productTitle);
                    $('#product_id').val(productID);
                    $('#price').val(productPrice);
                    $('#quantity').val(1);
                    $('#total-amount').val(productPrice);
                    $('#total-points').val(productPoints);
                    $('#product-current-quantity').val(productQuantity);
                    $('#total-points').data('id', productPoints);

                    $('#product-modal').modal('hide');
                });
            });
        });
    </script>
@endsection
