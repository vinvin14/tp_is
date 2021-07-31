@extends('interface.main')
@section('title','Unit List')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
    <a href="{{route('unit.create')}}" class="btn btn-primary"><i class="fas fa-fw fa-ruler"></i> Add
        New Unit</a>

    <div class="mt-4">
        <h5 class="border-bottom">Product Units</h5>
        <p class="font-italic">Here are all Product units that are ready to be used!</p>
    </div>
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Woops!</strong>  {{ Session::get('error')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(Session::has('response'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong>  {{ Session::get('response')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-ruler"></i> Product Units Table</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Unit Name</th>
                        <th>Second Level Unit</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($units as $unit)
                        <tr>
                            <td>{{$unit->unit_name}}</td>
                            <td>{{$unit->second_level_unit ?? 'No Data'}}</td>
                            <td>
                                <div class="text-center">
                                    <a href="{{route('unit.update', $unit->id)}}" class="pr-2"><i class="fas fa-fw fa-pencil-alt" title="Edit"></i></a>
                                    <a href="{{route('unit.destroy', $unit->id)}}" class="pl-2" onclick="return confirm('Are you sure you want to delete this unit?')"><i class="fas fa-fw fa-trash text-danger" title="Delete"></i></a>
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
