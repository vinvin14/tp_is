@extends('interface.main')
@section('title','New Order')
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
            <form action="{{ route('order.store', $transaction_id) }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="product" class="font-weight-bold">Product</label>
                    <input type="hidden" name="product_id" id="product_id">
                    <input type="hidden" id="product-current-quantity">
                    <input type="text" name="" id="product" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="product" class="font-weight-bold">Quantity</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <a class="btn btn-danger font-weight-bold" id="q-minus"><i class="fas fa-fw fa-minus"></i>
                            </a>
                        </div>
                        <input type="number" name="quantity" class="form-control text-center" min="1" id="quantity"
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
                            <input type="number" name="" id="price" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="product" class="font-weight-bold">Total Amount</label>
                            <input type="number" name="total_order_amount" step=".01" id="total-amount" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="product" class="font-weight-bold">Total Points</label>
                            <input type="number" name="total_points" step=".01" data-id="" id="total-points" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success form-control mt-2"><i class="fas fa-fw fa-check"></i> Add this Order</button>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Product List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        @foreach ($products as $product)
                            <div class="list-group-item list-group-item-action" data-id="{{ $product->id }}" id="product-option">
                                <div class="row">
                                    <div class="col-3 text-center">
                                        <img src="{{ asset($product->uploaded_img) }}" width="80px" height="80px" alt="product image">
                                    </div>
                                    <div class="col-9">
                                        <h6 class="font-weight-bold" id="product-title">{{ $product->item_title }}</h6>
                                        <div class="row">
                                            <div class="col-3">
                                                <small>Product Category</small>
                                                <p class="font-weight-bold">{{ $product->category_name }}</p>
                                            </div>
                                            <div class="col-3">
                                                <small>Quantity</small>
                                                <p class="font-weight-bold"><span id="product-quantity">{{ $product->current_quantity }}</span> {{ $product->unit }}</p>
                                            </div>
                                            <div class="col-3">
                                                <small>Price</small>
                                                <p class="font-weight-bold">₱<span id="product-price">{{ $product->price }}</span></p>
                                            </div>
                                            <div class="col-3">
                                                <small>Points</small>
                                                <p class="font-weight-bold" id="product-points">{{ $product->points }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            function round(num) {
                var m = Number((Math.abs(num) * 100).toPrecision(15));
                return Math.round(m) / 100 * Math.sign(num);
            }

            console.log(round(1.005));
            function totalAmount()
            {
                var productPrice = parseInt($('#price').val());
                var productQuantity = parseInt($('#quantity').val());

                $('#total-amount').val(round(productPrice*productQuantity));
            }
            function totalPoints()
            {
                var productPoints = $('#total-points').data('id');
                var productQuantity = parseInt($('#quantity').val());

                $('#total-points').val(round(productPoints*productQuantity));
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
