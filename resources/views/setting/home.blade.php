@extends('interface.main')
@section('title','Setting Page')
@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('includes/sbadmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-3 col-lg-3">
            <div class="list-group">
                <a href="{{route('setting.home')}}" class="list-group-item list-group-item-action active">Account Information</a>
                <a href="{{route('setting.changepassword')}}" class="list-group-item list-group-item-action">Change Password</a>
                <a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>
              </div>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-9">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Username</label>
                        <div class="font-weight-bold">{{$account->username}}</div>
                    </div>
                    <div class="form-group">
                        <label for="">Account Role</label>
                        <div class="font-weight-bold">{{$account->role}}</div>
                    </div>
                    <div class="form-group">
                        <label for="">Account Owner</label>
                        <div class="font-weight-bold">{{$account->account_owner}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection