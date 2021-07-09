@extends('interface.main')
@section('title','ProductOld')
@section('styles')
    <!-- Custom styles for this page -->

@endsection
@section('main')
    <a href="{{route('specification.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Specification List</a>
    <div class="card shadow-sm mt-2">
        <div class="card-header font-weight-bold text-primary"><i
                class="fas fa-fw fa-blender"></i> Product Specification Details</div>
        <div class="card-body">
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
            <div class="row">
                <div class="col-3">
                    <div class="p-0">
                        <img class="border p-2 shadow-sm" src="{{ $specification->uploaded_img }}" alt="" width="250px"
                             height="250px">
                    </div>
                </div>
                <div class="col-9 ">
                    <h3 class="font-weight-bold">{{ $specification->item_title }}</h3>
                    <div class="mt-3">
                        <label for="">Description: </label>
                        <textarea name="" id="" cols="30" rows="3" class="form-control"
                                  readonly>{{ $specification->description ?? 'No Data' }}</textarea>
                        <div class="row mt-3">
                            <div class="col-3">
                                <label for="">Product Category</label>
                                <div class="font-weight-bold">{{ $specification->category_name }}</div>
                            </div>
                            <div class="col-3">
                                <label for="">Unit</label>
                                <div class="font-weight-bold">{{ $specification->unit }}</div>
                            </div>
                            <div class="col-3">
                                <label for="">Price</label>
                                <div class="font-weight-bold">₱ {{ number_format($specification->price) }}</div>
                            </div>
                            <div class="col-3">
                                <label for="">Points per Purchase</label>
                                <div class="font-weight-bold">{{ number_format($specification->points) }} point(s)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('specification.update', $specification->id) }}" class="btn btn-primary form-control mt-5"><i class="fas fa-fw fa-edit"></i> Update this
                Specification
            </a>
        </div>
    </div>
@endsection
