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
                <a href="{{route('setting.home')}}" class="list-group-item list-group-item-action">Account Information</a>
                <a href="{{route('setting.changepassword')}}" class="list-group-item list-group-item-action active">Change Password</a>
                <a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>
              </div>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-9">
            <div class="card">
                <div class="card-body">
                    @include('system-message')
                   <form action="{{route('setting.savePassword', $account->id)}}" method="post">
                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" class="form-control" value="{{$account->username}}" name="username" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Current Password</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="">New Password</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" value="{{old('new_password')}}">
                            <small class="text-danger" id="password-error"></small>
                        </div>
                        <div class="form-group">
                            <label for="">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="{{old('confirm_password')}}">
                            <small class="text-danger" id="password-error2"></small>
                        </div>
                        <button type="submit" class="btn btn-success" id="submit" style="display: none">Save Changes</button>
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('#confirm_password').on('input', function () {
            var newPassword = $('#new_password').val();
            var currentPassword = $(this).val();
            console.log(newPassword, currentPassword);
            if (newPassword != currentPassword) {
                $('#password-error, #password-error2').text('Password did not match!');
                $('#submit').hide();
            } else {
                $('#password-error, #password-error2').text('');
                $('#submit').show();
            }
        });
    });
</script>
@endsection