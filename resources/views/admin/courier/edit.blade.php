@extends('adminLayout.master')
@section('title')
    <title>Edit Coupon</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Coupon</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="/admin/courier/{{$courier->id}}" name="form-courier" method="POST" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            {{-- <input type="hidden" name="action_type"/> --}}
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Nama Kupon</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $courier->name) }}" id="title" placeholder="Masukan nama kupon">
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>

                                 <div class="form-group">
                                    <label for="title">Inisial</label>
                                    <input type="text" class="form-control" name="initial" value="{{ old('initial', $courier->initial) }}" id="title" placeholder="Masukan nama kupon">
                                    <p class="text-danger">{{ $errors->first('initial') }}</p>
                                </div>
                                
                            </div>
                            <div class="box-footer mb-3 ml-4">
                                <button class="btn btn-primary">Save</button>
                                <a href="{{ URL::previous() }}" class="btn btn-danger ml-2">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

