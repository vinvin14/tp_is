@extends('interface.main')
@section('title','Notification Center')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    @include('system-message')
    <div class="card">
        <div class="card-body">
            Notification List
        </div>
    </div>
    <div class="mt-2">
        @foreach ($notifications as $notification)
            <div class="list-group shadow-sm my-1">
                <button type="button" class="list-group-item list-group-item-action">
                    <small class="font-weight-bold text-info float-right">As of {{$notification->created_at}}</small>
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-xs-6">
                            @if ($notification->uploaded_img != null)
                                <img src="{{$notification->uploaded_img}}" class="border-3 shadow-sm" width="150px" height="150px" alt="">
                            @else
                                <small>No Image Available</small>
                            @endif
                        </div>
                        <div class="col-lg-8 col-md-6 col-xs-6">
                            <div class="my-1">
                                <small>Product</small>
                                <div class="font-weight-bold">{{$notification->title}}</div>
                            </div>
                            <div class="my-1">
                                <small>Details</small>
                                <p @if ($notification->type == 'expired') class="text-danger"> @endif{{$notification->details}}</p>
                            </div>
                            <div class="my-1">
                                <small>Actions</small>
                                <div>
                                    <a href="{{route('product.show', $notification->reference_id)}}" class="px-2"><i class="fas fa-eye"></i> View Product</a> |
                                    <a href="{{route('notification.tagAsDone', $notification->id)}}" class="px-2"><i class="fas fa-check text-success"></i> Tag as Done</a> |
                                    <a href="{{route('notification.destroy', $notification->id)}}" onclick="return confirm('Are you sure you want to delete this notification?')" class="px-2"><i class="fas fa-trash text-danger"></i> Trash this Notification</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </button>
              </div>
              @endforeach
              <div class="m-3">
                  {{$notifications->render()}}
              </div>
    </div>
    
@endsection
@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('includes/sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('includes/sbadmin/js/demo/datatables-demo.js') }}"></script>

@endsection
