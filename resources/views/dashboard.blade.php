@extends('interface.main')
@section('title', 'Dashboard')
@section('main')
<div class="row">
    <div class="col-lg-6 col-md-6 col-xs-12">
        <div class="card shadow-sm" style="min-height: 140px">
            <div class="card-header">
                <i class="fas fa-bell"></i> Notifications
            </div>
            <div class="card-body">
                @if (!empty ($notifications))
                   <a href="#" class="font-weight-bold">{{count($notifications)}} notification(s) that requires your attention</a>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-xs-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="fas fa-trophy"></i> Total Monthly Sale
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12">
                        <small>Total Items</small>
                        <div class="h3 font-weight-bold">{{$total_sale->totalItems}}</div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12">
                        <small>Total Sale Amount</small>
                    <div class="h3 font-weight-bold">₱{{number_format($total_sale->totalAmount)}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row my-3">
    <div class="col-lg-6 col-md-6 col-xs-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="fas fa-money-bill-wave"></i> Top Selling Product
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto">
                @forelse ($top_selling_products as $product)
                    <div class="list-group mb-1">
                        <a href="{{route('product.show', $product->id)}}" class="list-group-item list-group-item-action">
                            <div class="row">
                                <div class="col-6">
                                    <div class="font-weight-bold">{{$product->title}}</div>
                                </div>
                                <div class="col-6">
                                    <div class="font-weight-bold">{{$product->totalSale}} @if($product->totalSale > 1) {{$product->unit_plural_name}} @else {{$product->unit}} @endif</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="font-weight-bold">No Record(s) Found</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-xs-12"></div>
</div>
@endsection
