@extends('interface.main')
@section('title','Add new Customer')
@section('main')
    <div class="">
        <form action="{{route('customer.store')}}" method="post">
            @csrf
        <a href="{{route('customer.list')}}" class="font-weight-bold"><i class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Customer List</a>
        <div class="card shadow-sm mt-3">
            <div class="card-header text-primary font-weight-bold">
                <i class="fas fa-fw fa-user-plus"></i> New Customer
            </div>
            <div class="card-body py-4">
                @if(Session::has('response'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong>  {{ Session::get('response')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                    @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Woops!</strong>  {{ Session::get('error')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                <div class="row">
                    <div class="col-4">
                        <label for="firstname" class="font-weight-bold">First Name</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" required>
                    </div>
                    <div class="col-4">
                        <label for="middlename" class="font-weight-bold">Middle Name</label>
                        <input type="text" name="middlename" id="middlename" class="form-control" required>
                    </div>
                    <div class="col-4">
                        <label for="lastname" class="font-weight-bold">Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" required>
                    </div>
                </div>

                <div class="row py-2" >
                    <div class="col-4">
                        <label for="date_of_birth" class="font-weight-bold">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control">
                    </div>
                    <div class="col-4">
                        <label for="points" class="font-weight-bold">Points</label>
                        <input type="number" step=".001" class="form-control">
                    </div>
                    <div class="col-4">
                        <label for="customer_type" class="font-weight-bold">Customer Type</label>
                        <select name="customer_type" id="customer_type" class="form-control">
                            <option value="">-</option>
                            <option value="guest">Guest</option>
                            <option value="member">Member</option>
                        </select>
                    </div>
                </div>
                <div class="">
                    <label for="address" class="font-weight-bold">Address</label>
                    <textarea name="address" class="form-control" required></textarea>
                    <small class="font-italic text-muted">Note: Address should be in this format: (# of Street,
                        Barangay, City, State)
                    </small>
                </div>

                <button class="btn btn-success mt-3 form-control">Add Customer</button>
            </div>
        </div>
        </form>
    </div>
@endsection
