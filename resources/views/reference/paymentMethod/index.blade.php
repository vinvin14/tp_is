@extends('interface.main')
@section('title','Payment Methods')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    <a href="{{route('paymentMethod.create')}}" class="btn btn-primary"><i class="fas fa-fw fa-money-bill"></i> New Payment Method</a>

    <div class="mt-4">
        <h5 class="border-bottom">Payment Methods</h5>
        <p class="font-italic">Here are all Payment Methods that are ready to be used!</p>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-money-bill"></i> Payment Methods Table</h6>
        </div>
        <div class="card-body">
            @include('interface.system-messages')
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Payment Method</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($paymentMethods as $paymentMethod)
                        <tr>
                            <td>{{$paymentMethod->name}}</td>
                            <td>
                                <div class="text-center">
                                    <a href="{{route('paymentMethod.edit', $paymentMethod->id)}}" class="pr-2"><i class="fas fa-fw fa-pencil-alt" title="Edit"></i></a>
                                    <a href="{{route('paymentMethod.destroy', $paymentMethod->id)}}" class="pl-2" onclick="return confirm('Are you sure you want to delete this Payment Method?')"><i class="fas fa-fw fa-trash text-danger" title="Delete"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
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
