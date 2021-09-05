@extends('layouts/app-sbadmin')
@section('body_class','bg-gradient-primary')

@section('content')
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-5 col-lg-6 col-md-9 mt-5">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">TATAK<b class="text-danger">PINAS!</b></h1>
                                    </div>
                                    @include('interface.system-messages')
                                    <form class="user" id="login" action="{{route('login_attempt')}}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" name="username" value="{{ (old('username'))  }}" class="form-control form-control-user"
                                                   id="exampleInputEmail" aria-describedby="emailHelp"
                                                   placeholder="Enter Username..." required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password"  class="form-control form-control-user"
                                                   id="exampleInputPassword" placeholder="Enter Password" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" name="rememberme" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-danger btn-user btn-block">
                                            Login
                                        </button>

                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>



<div id="loading" style="position:absolute;top:0px;height:100%; width:100%; background-color:black !important;z-index:999;overflow:hidden; display:none">
    <div class="container text-center" style="margin-top: 25vh">
        {{-- <h5 style="margin-top: 20vh;position: absolute;left:40vw">System is Initializing, Please wait. . . . . . </h5> --}}
        <img src="/storage/loading_images/5.gif" alt="" width="450px">
    </div>
</div>
    <!-- Modal -->
{{-- <div class="modal fade" id="loading-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <h5>System is Initializing, Please wait. . . . . . </h5>
            <img src="/storage/loading_images/1.gif" alt="" width="450px">
        </div>
      </div>
    </div>
  </div> --}}
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('#login').submit(function (event) {
            $('#loading').show();
            // event.preventDefault();
        });
    });
</script>
@endsection