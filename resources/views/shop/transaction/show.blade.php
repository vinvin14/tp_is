@extends('interface.main')
@section('title','Transaction')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .modal-lg {
            max-width: 80%;
        }
        .product-title {
            cursor: pointer;
        }
        .card-img-top {
            opacity: .5;
        }
        .card-img-top:hover {
            opacity: 1;
            transition: opacity 1s;
        }
        .product-selected {
            opacity: 1;
        }
        #product-container {
            cursor: pointer;
        }
    </style>
@endsection
@section('main')
    <a href="{{route('transaction.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Transaction List</a>
    @include('system-message')
    <div class="card">
        <div class="card-body">
            <h5 class="font-weight-bold">Current Transaction</h5>
            <hr>
            <a href="{{ route('transaction.update', $transaction->transaction_id) }}"
                class="btn btn-secondary">
                 <i class="fas fa-fw fa-pencil-alt"></i> Update this Transaction</a>
            <div class="row mt-3">
                <div class="col-3">
                    <small class="font-weight-bold">Customer</small>
                    <div>{{ $transaction->lastname   }}
                        {{ $transaction->firstname }} {{ $transaction->middlename }}</div>
                </div>
                <div class="col-3">
                    <small class="font-weight-bold">Ticket Number</small>
                    <div>{{ $transaction->ticket_number }}</div>
                </div>
                <div class="col-3">
                    <small class="font-weight-bold">Claim Type</small>
                    <div>{{ $transaction->claim_type }}</div>
                </div>
                <div class="col-3">
                    <small class="font-weight-bold">Payment Method</small>
                    <div>{{ $transaction->payment_method }}</div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-3">
                    <small class="font-weight-bold">Total Amount</small>
                    <div>₱{{ $transaction->total_amount   }}</div>
                </div>
                <div class="col-3">
                    <small class="font-weight-bold">Total Points</small>
                    <div>{{ $transaction->total_amount   }}</div>
                </div>
                <div class="col-3">
                    <small class="font-weight-bold">Transaction Status</small>
                    <div>{{ $transaction->trans_status   }}</div>
                </div>
                <div class="col-3">
                    <small class="font-weight-bold">Transaction Date</small>
                    <div>{{ $transaction->transaction_date }}</div>
                </div>
            </div>
            <div class="form-group mt-3">
                <small class="font-weight-bold">Remarks</label>
                <textarea name="" id="" cols="30" rows="10" class="form-control" readonly>{{ $transaction->remarks }}</textarea>
            </div>
        </div>
    </div>
    <hr>
    <div class="card">
        <div class="card-body">
            <h5>List of Existing Orders within this Transaction</h5>
            @if($transaction->trans_status == 'pending')
            <button id="add-product" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add Product</button>
                {{-- <a href="{{ route('order.add', $transaction->transaction_id) }}" class="btn btn-primary">
                    <i class="fas fa-fw fa-plus"></i> Add Product</a> --}}
            @endif
            @if($transaction->trans_status == 'pending')
                <a href="{{ route('transaction.checkout', $transaction->transaction_id) }}" class="btn btn-danger shadow-sm ml-3">
                    <i class="fas fa-fw fa-money-bill"></i> Proceed to Checkout</a>
            @endif

            <hr>
            {{-- <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Product</th>
                        <th>Product Category</th>
                        <th>Unit</th>
                        <th>Points</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <div class="text-center">
                                    <a href="{{route('order.update', $order->id)}}" class="pr-2"><i
                                            class="fas fa-fw fa-pencil-alt" title="Update"></i></a>
                                    <a href="{{route('order.destroy', $order->id)}}" class="pl-2"
                                       onclick="return confirm('Are you sure you want to delete this order?')"><i
                                            class="fas fa-fw fa-trash text-danger" title="Delete"></i></a>
                                </div>
                            </td>
                            <td>{{$order->item_title}}</td>
                            <td>{{$order->category_name}}</td>
                            <td>{{$order->unit}}</td>
                            <td>{{$order->points}}</td>
                            <td>{{$order->quantity}}</td>
                            <td>₱ {{$order->price}}</td>
                            <td>₱ {{$order->total_order_amount}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfooter>
                        <tr>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th>Total Order Amount</th>
                            <th>₱</th>
                        </tr>
                    </tfooter>
                </table>
            </div> --}}
        </div>
    </div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          {{-- <h5 class="modal-title" id="exampleModalLabel">List of Available Product</h5> --}}
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
           <div class="row container-fluid">
                <div class="col-xs-12 col-lg-8">
                    <h5 class="text-center">List of Available Products</h5>
                    <label for="product-category">Category</label>
                    <select name="" id="product-category" class="form-control">
                        <option value="">-</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <label for="keyword">Search Keyword</label>
                    <input type="text" id="keyword" class="form-control">

                    <div class="row" id="products" style="max-height: 600px; overflow-y: auto">
                        {{-- ajax items --}}
                    </div>
               </div>
               <div class="col-xs-12 col-lg-4 border-left">
                    <h5 class="text-center">Product Selected Details</h3>
                    <form action="{{ route('order.store', $transaction->transaction_id) }}">
                        <div class="container" id="order-details"></div>
                    </form>
               </div>
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
    {{-- jquery --}}
    <script>
        $(document).ready(function () {
            $('#product-category').on('input', function () {
                var content = $('#products');
                $.ajax({
                    url: '/ajax/get/products/'+ $(this).val(),
                    type: 'get',
                    success: function (data) {
                        content.html('');
                        content.append('');
                        data.forEach(function (index) {
                            content.append('' +
                                    '<div class="col-xs-12 col-md-6 col-lg-3 my-2" id="product-container" data-id="'+index.id+'">' +
                                    '<div class="card shadow-sm" style="width: 13rem;">' +
                                    '<img class="card-img-top" height="120px" src="'+(index.uploaded_img ? index.uploaded_img : '/storage/utilities/no_image.png')+'" alt="Card image cap">' +
                                    '<div class="card-body text-center">' +
                                    '<div class="font-weight-bold text-truncate" id="title" title="'+ index.title +'">'+ index.title +'</div>' +
                                    '<div class="font-weight-bold text-truncate price">₱<span id="price">'+ index.price +'</span></div>' +
                                    '<span id="qty" data-qty="'+ index.qty +'" data-unit="'+ index.unit +'"></span>' +
                                    // '<a href="#" class="btn btn-primary">Go somewhere</a>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>'
                                );

                            });
                            $("#keyword").on("keyup", function() {
                                var value = $(this).val().toLowerCase();
                                console.log(value);
                                $('#products').find(".card").filter(function() {
                                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                });
                            });

                            $('div[id="product-container"]').click(function (e) {
                                var productID = $(this).data('id');

                                // console.log(e.target);
                                $(this).find('.card').addClass('product-selected border border-success');
                                $(this).find('.card-img-top').css('opacity', 1);

                                $('#order-details').html('' +
                                    '<div class="form-group mt-5">' +
                                        '<label>Product Title</label>' +
                                        '<div class="font-weight-bold text-info">'+ $(this).find('#title').text() +'</div>' +
                                    '</div>' +
                                    '<div class="form-group mt-2">' +
                                        '<label>Product Price</label>' +
                                        '<div class="font-weight-bold text-info">'+ $(this).find('.price').text() +'</div>' +
                                    '</div>' +
                                    '<div class="form-group mt-2">' +
                                        '<label>Product Unit</label>' +
                                        '<div class="font-weight-bold text-info">'+ $(this).find('#qty').data('unit')  +
                                    '</div>' +
                                    '<div class="form-group mt-2">' +
                                        '<label>Product Quantity</label>' +
                                        '<div class="font-weight-bold text-info"><span id="remaining-qty">'+ $(this).find('#qty').data('qty') +'</span> Remaining</div>' +
                                    '</div>' +
                                    '<div class="form-group mt-2">' +
                                        '<label>Buying Quantity</label>' +
                                        '<div class="input-group mb-2">' +
                                            '<div class="input-group-prepend">' +
                                                '<span class="btn btn-danger font-weight-bold" id="q-minus"><i class="fas fa-fw fa-minus"></i></span>' +
                                            '</div>' +
                                            '<input type="number" class="form-control col-4 text-center" id="buying-qty" name="qty">' +
                                            '<div class="input-group-append">' +
                                                ' <span class="btn btn-success font-weight-bold" id="q-add"><i class="fas fa-fw fa-plus"></i></span>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                    '<button type="submit" class="btn btn-success btn-block">Add this Product</button>'
                                );

                                $('#buying-qty').on('blur', function () {
                                    // console.log($(this).val());
                                    var remainingQty = parseInt($('#remaining-qty').text());
                                    if ($(this).val() > remainingQty) {
                                        alert('Item has insufficient quantity for the request!');
                                        $(this).val(remainingQty);
                                    }
                                });

                                $(this).siblings().click(function () {
                                    if (productID != $(this).data('id')) {
                                        $('div[data-id='+productID+']').find('.card').removeClass('product-selected border border-success');
                                        $('div[data-id='+productID+']').find('.card-img-top').css('opacity', 0.5);
                                    }
                                });
                            });
                    },
                    error: function (data) {
                            content.html('<h3 class="text-center m-4">'+data.responseJSON+'</h3>');
                    }
                });
            });
        });
    </script>




    <!-- Page level plugins -->
    <script src="{{ asset('includes/sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('includes/sbadmin/js/demo/datatables-demo.js') }}"></script>
@endsection
