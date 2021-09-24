@extends('interface.main')
@section('title','Sales Statistics')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
<div class="card">
    <div class="card-body">
        <h5 class="font-weight-bold">Generate Sales Statistics</h5>
        <form action="{{route('sales.generate')}}" method="post">
            <div class="form-group">
                <label for="">From</label>
                <input type="date" name="date_from" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">To</label>
                <input type="date" name="date_to" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Get Sales</button>
        </form>
    </div>
</div>

@endsection