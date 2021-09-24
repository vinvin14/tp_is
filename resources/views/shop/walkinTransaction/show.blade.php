@extends('interface.main')
@section('title','Walk-in Transaction')
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
        .search-hidden {
            display: none;
        }
    </style>
@endsection
@section('main')
    <a href="{{route('walkinTransaction.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Walk-in Transaction List</a>
    @include('system-message')
    <div class="card mt-2">
        <div class="card-header">
            <div class="h5">Walk-in Transaction Details</div>
        </div>
        <input type="hidden" id="transaction-id" value="{{$transaction->id}}">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <label for="">Transaction Date</label>
                    <div class="font-weight-bold">{{$transaction->transaction_date}}</div>
                </div>
                <div class="col-3">
                    <label for="">Ticket Number</label>
                    <div class="font-weight-bold">{{$transaction->transaction_date}}</div>
                </div>
                <div class="col-3">
                    <label for="">Payment Method</label>
                    <div class="font-weight-bold">{{$transaction->payment_method_name}}</div>
                </div>
                <div class="col-3">
                    <label for="">Claim Type</label>
                    <div class="font-weight-bold">{{$transaction->claim_type_name}}</div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Remarks</label>
                <textarea name="" id="" cols="30" rows="3" class="form-control" readonly>{{$transaction->remarks}}</textarea>
            </div>
        </div>    
    </div>
    <hr>
    <div class="card">
        <div class="card-body">
            <h1>Walk-in Orders</h1>
            @if($transaction->trans_status == 'pending')
            <button id="add-product" class="btn btn-primary mt-2" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#exampleModal">Add Product</button>
                {{-- <a href="{{ route('order.add', $transaction->transaction_id) }}" class="btn btn-primary">
                    <i class="fas fa-fw fa-plus"></i> Add Product</a> --}}
            @endif
            @if($transaction->trans_status == 'pending')
                {{-- <a href="{{ route('transaction.checkout', $transaction->transaction_id) }}" class="btn btn-danger shadow-sm ml-3">
                    <i class="fas fa-fw fa-money-bill"></i> Proceed to Checkout</a> --}}
                    @if (!empty ($orders->toArray()))
                    <a href="#" id="checkout" class="btn btn-danger shadow-sm ml-3">
                        <i class="fas fa-fw fa-money-bill"></i> Proceed to Checkout</a>
                    @endif
            @endif

            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        @if($transaction->trans_status == 'pending')
                        <th></th>
                        @endif
                        <th>Product</th>
                        <th>Product Category</th>
                        <th>Qty</th>
                        <th>Points</th>
                        <th>Discount Amount</th>
                        <th>Price</th>
                        <th>Total Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            @if($transaction->trans_status == 'pending')
                            <td>

                                <div class="text-center">
                                    <a href="#" id="update-order" data-id="{{ $order->id }}" class="pr-2"><i
                                            class="fas fa-fw fa-pencil-alt" title="Update" class="btn btn-primary" data-toggle="modal" data-target="#updateOrderModal"></i></a>
                                    <a href="{{route('walkin.order.destroy', $order->id)}}" class="pl-2"
                                       onclick="return confirm('Are you sure you want to delete this order?')"><i
                                            class="fas fa-fw fa-trash text-danger" title="Delete"></i></a>
                                </div>

                            </td>
                            @endif
                            <td>{{ $order->title }}</td>
                            <td>{{ $order->category }}</td>
                            <td>{{ $order->qty }} {{ $order->unit }}</td>
                            <td>{{ $order->total_points }}</td>
                            <td>₱ {{ $order->discount_amount }}</td>
                            <td>₱ {{ $order->price }}</td>
                            <td>₱ {{ $order->total_amount }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfooter>
                        <tr>
                            @if($transaction->trans_status == 'pending')
                            <th class="border-right-0"></th>
                            @endif
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th class="border-right-0"></th>
                            <th>Total Order Amount</th>
                            <th>₱ {{ $total_order_amount }}</th>
                        </tr>
                    </tfooter>
                </table>
            </div>
        </div>
    </div>



<!--Product Order Modal -->
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
                    <form action="{{ route('walkin.order.store', $transaction->id) }}" method="post">
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


<!--Order Update Modal -->
<div class="modal fade" id="updateOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update Order</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
              <label for="">Product Title</label>
              <div id="update-title"></div>
          </div>
          <div class="form-group">
              <label for="">Quantity</label>
              <input type="number" id="update-qty" class="form-control">
          </div>
          <div class="form-group">
              <label for="">Discounted Amount</label>
              <input type="number" id="update-discount" step="0.01" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="update-save" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="checkoutLoadingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <img src="/storage/loading-image/3.gif" alt="">
        </div>
      </div>
    </div>
  </div>


@endsection
@section('scripts')
    {{-- jquery --}}
    <script>
        $(document).ready(function () {
            addOrder();
            updateOrder();

            $('#checkout').click(function () {
                console.log($('#transaction-id').val());
                Swal.fire({
                    title: 'Are you sure you want to Proceed to Checkout?',
                    text: "Please review all orders since You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Proceed!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $('#checkoutLoadingModal').modal('show');
                        // console.log($('#transaction-id').val());
                        window.location.href = '/walkintransaction/checkout/'+$('#transaction-id').val();
                    }
                    })
             });
            });
    </script>

    <!-- Page level plugins -->
    <script src="{{ asset('includes/sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('includes/sbadmin/js/demo/datatables-demo.js') }}"></script>
    <script src="{{ asset('includes/js/swal.js') }}"></script>
    <script src="{{ asset('includes/js-view/add-order.js') }}"></script>
    <script src="{{ asset('includes/js-view/update-order.js') }}"></script>
@endsection
