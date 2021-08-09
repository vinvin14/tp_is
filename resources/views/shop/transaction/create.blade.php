@extends('interface.main')
@section('title','New Transaction')
@section('styles')
    <!-- Custom styles for this page -->
@endsection
@section('main')
    <a href="{{route('transaction.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Transaction List</a>
    <div class="col-xs-12 col-md-8 col-lg-8">
        <div class="card w-75 shadow-sm mt-2">
            <div class="card-header font-weight-bold text-primary">Add New Transaction</div>
            <div class="card-body">
                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Woops!</strong> {{ Session::get('error')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <form action="{{ route('transaction.store') }}" method="post">
                    <div class="row">
                        <div class="col-6">
                            <label for="">Customer</label>
                            <input type="hidden" name="customer" id="customer" value="{{old('customer')}}">
                            <input type="text" name="customer_name" value="{{old('customer_name')}}" id="customer_select" class="form-control" readonly required>
                        </div>
                        <div class="col-6">
                            <label for="">Transaction Date</label>
                            <input type="date" name="transaction_date" class="form-control" value="{{old('transaction_date')}})" required {{ $errors->first('transaction_date')}}>
                            @error('transaction_date')
                                <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-4">
                            <label for="">Claim Status</label>
                            <select name="claim_type" class="form-control" id="">
                                <option value="">-</option>
                                <option value="pick-up" {{(old('claim_type') == 'pick-up') ? 'selected' : ''}}>Pick up</option>
                                <option value="deliver" {{(old('claim_type') == 'deliver') ? 'selected' : ''}}>Deliver</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="">Transaction Status</label>
                            <select name="trans_status" id="" class="form-control" required>
                                <option value="pending" {{(old('trans_status') == 'pending') ? 'selected' : ''}}>Pending</option>
                                <option value="completed" {{(old('trans_status') == 'completed') ? 'selected' : ''}}>Completed</option>
                                <option value="unclaimed" {{(old('trans_status') == 'unclaimed') ? 'selected' : ''}}>Unclaimed</option>
                                <option value="unpaid" {{(old('trans_status') == 'unpaid') ? 'selected' : ''}}>Unpaid</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="">Payment Method</label>
                            <select name="payment_method_id" id="" class="form-control" required>
                                <option value="">-</option>
                                @foreach( $paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" id="" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary form-control mt-3">Add Transaction</button>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="customer_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">List of Customers</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="search-customer" class="form-control col-4 mb-3"
                           placeholder="Search Customer here">
                    <div class="list-group" style="max-height: 500px;overflow-y: auto">
                        @foreach ($customers as $customer)
                            <div href="#" class="list-group-item list-group-item-action" id="customer-option"
                                 data-id="{{ $customer->id }}">
                                <div class="row" >
                                    <div class="col-2">
                                        <small class="font-weight-bold">First Name</small>
                                        <div id="firstname">{{ $customer->firstname }} </div>
                                    </div>
                                    <div class="col-2">
                                        <small class="font-weight-bold">Middle Name</small>
                                        <div id="middlename">{{ $customer->middlename }}</div>
                                    </div>
                                    <div class="col-2">
                                        <small class="font-weight-bold">Last Name</small>
                                        <div id="lastname">{{ $customer->lastname }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="font-weight-bold">Address</small>
                                        <textarea name="" id="" cols="30" rows="1" class="form-control"
                                                  readonly>{{ $customer->address }}</textarea>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#customer_select').click(function () {
                $('#customer_modal').modal('show');
            });

            $('div[id="customer-option"]').click(function () {
                var customerID = $(this).data('id');
                var firstname = $(this).find('#firstname').text();
                var middlename = $(this).find('#middlename').text();
                var lastname = $(this).find('#lastname').text();
                var fullname = lastname + ', ' + firstname + ' ' + middlename;

                $('#customer').val(customerID);
                $('#customer_select').val(fullname);
                //close the modal
                $('#customer_modal').modal('hide');
            });

            $("#search-customer").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#customer-option .row").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        })
    </script>
@endsection
