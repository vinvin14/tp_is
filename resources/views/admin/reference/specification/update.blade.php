@extends('interface.main')
@section('title','ProductOld')
@section('styles')
    <!-- Custom styles for this page -->

@endsection
@section('main')
    @if(! empty($errors->all()))
        {{dd($errors->all())}}
    @endif
    <a href="{{route('specification.list')}}" class="font-weight-bold"><i
            class="fas fa-fw fa-arrow-alt-circle-left"></i> Back to Specification List</a>
    <div class="card shadow-sm mt-2">
        <div class="card-header font-weight-bold text-primary"><i
                class="fas fa-fw fa-blender"></i> Product Specification Details
        </div>
        <div class="card-body">
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Woops!</strong>  {{ Session::get('error')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <form action="{{ route('specification.upsave', $specification->id) }}" method="post" role="form" enctype="multipart/form-data">
                @csrf
            <div class="row">
                <div class="col-3">
                    <div class="p-0">
                        <label for="" class="font-weight-bold">Current Image</label>
                        <img class="border p-2 shadow-sm" src="{{ $specification->uploaded_img }}" alt="" width="250px"
                             height="250px">
                    </div>
                    <small class="text-danger font-italic">To change the current image you can click here to upload new image</small>
                    <input type="file" name="image" class="form-control-file mt-2">
                </div>
                <div class="col-9 ">
                    <div class="form-group">
                        <label for="" class="font-weight-bold">Product Title</label>
                        <input type="text" name="item_title" value="{{ $specification->item_title }}" class="form-control">
                    </div>
                    <div class="mt-3">
                        <label for="">Description: </label>
                        <textarea name="description" id="" cols="30" rows="3"
                                  class="form-control">{{ $specification->description  }}</textarea>
                        <div class="row mt-3">
                            <div class="col-3">
                                <label for="" class="font-weight-bold">Product Category</label>
                                <select name="product_category" id="" class="form-control" required>
                                    <option value="">-</option>
                                    @foreach( $product_categories as $category )
                                        <option value="{{ $category->id }}"
                                                @if( $specification->product_category == $category->id)
                                                selected
                                            @endif>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="" class="font-weight-bold">Product Unit</label>
                                <select name="unit" id="" class="form-control" required>
                                    <option value="">-</option>
                                    @foreach( $units as $unit )
                                        <option value="{{ $unit->id }}"
                                                @if($specification->unit == $unit->id)
                                                    selected
                                                @endif>
                                            {{ $unit->unit_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="" class="font-weight-bold">Price</label>
                                <input type="number" step=".01" name="price" value="{{ $specification->price }}" class="form-control">
                            </div>
                            <div class="col-3">
                                <label for="" class="font-weight-bold">Points per Purchase</label>
                                <input type="number" step=".01" name="points" value="{{ $specification->points }}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success form-control mt-5"><i
                    class="fas fa-fw fa-save"></i> Save Changes
            </button>
            </form>
        </div>
    </div>
@endsection
