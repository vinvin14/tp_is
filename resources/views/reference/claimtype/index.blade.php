@extends('interface.main')
@section('title','Claim Type List')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    <h5 class="border-bottom">Claim Type</h5>
    <p class="font-italic">Here are all Claim Types that can be referenced!</p>
    <a href="{{route('claimType.create')}}" class="btn btn-primary my-3 shadow-sm"><i class="fas fa-fw fa-plus"></i>New Claim Type</a>
    @include('interface.system-messages')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-ruler"></i> Claim Type Table</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Claim Type Name</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($claimTypes as $claimType)
                        <tr>
                            <td>{{$claimType->name}}</td>
                            <td>
                                <div class="text-center">
                                    <a href="{{route('claimType.edit', $claimType->id)}}" class="pr-2"><i class="fas fa-fw fa-pencil-alt" title="Edit"></i></a>
                                    <a href="{{route('claimType.destroy', $claimType->id)}}" class="pl-2" onclick="return confirm('Are you sure you want to delete this claim type?')"><i class="fas fa-fw fa-trash text-danger" title="Delete"></i></a>
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
