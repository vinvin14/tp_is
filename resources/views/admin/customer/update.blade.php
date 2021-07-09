@extends('interface.main')
@section('title','Update Customer Information')
@section('main')
    <div class="">
        <form action="{{route('customer.upsave', $customer->id)}}" method="post">
            @csrf
            <a href="{{route('customer.list')}}" class="font-weight-bold"><i class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Customer List</a>
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-dark text-light">Customer Details</div>
                <div class="card-body py-4">
                    <div class="row">
                        <div class="col-4">
                            <label for="firstname" class="font-weight-bold">First Name</label>
                            <input type="text" name="firstname" value="{{ $customer->firstname }}" id="firstname" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <label for="middlename" class="font-weight-bold">Middle Name</label>
                            <input type="text" name="middlename" value="{{ $customer->middlename }}" id="middlename" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <label for="lastname" class="font-weight-bold">Last Name</label>
                            <input type="text" name="lastname" id="lastname" value="{{ $customer->lastname }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="row py-2" >
                        <div class="col-4">
                            <label for="date_of_birth" class="font-weight-bold">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ $customer->date_of_birth }}" class="form-control">
                        </div>
                        <div class="col-4">
                            <label for="points" class="font-weight-bold">Points</label>
                            <input type="number" name="total_points" step=".001" value="{{ $customer->current_points }}" class="form-control">
                        </div>
                        <div class="col-4">
                            <label for="customer_type" class="font-weight-bold">Customer Type</label>
                            <select name="customer_type" id="customer_type" class="form-control">
                                <option value="">-</option>
                                <option value="guest"
                                        @if($customer->customer_type == 'guest')
                                            selected
                                        @endif
                                >Guest</option>
                                <option value="member" @if($customer->customer_type == 'member') selected @endif>Member</option>
                            </select>
                        </div>
                    </div>
                    <div class="">
                        <label for="address" class="font-weight-bold">Address</label>
                        <textarea name="address" class="form-control" required>{{ $customer->address }}</textarea>
                        <small class="font-italic text-muted">Note: Address should be in this format: (# of Street,
                            Barangay, City, State)
                        </small>
                    </div>

                    <button class="btn btn-success mt-3 float-right"><i class="fas fa-fw fa-save"></i> Save Information</button>
                    <a href="{{ route('customer.show', $customer->id) }}" class="btn btn-danger mt-3 "><i class="fas fa-fw fa-times"></i> Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection
