@extends('adminLayout.master')
@section('title')
    <title>Create Coupon</title>
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
                        <form action="/admin/courier" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Nama Kurir</label>
                                    <input type="text" class="form-control" name="name" id="title" value="{{ old('name') }}" placeholder="Masukan nama kupon">
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>
                                
                                 <div class="form-group">
                                    <label for="title">Inisial</label>
                                    <input type="text" class="form-control" name="initial" id="title" value="{{ old('initial') }}" placeholder="Masukan nama kupon">
                                    <p class="text-danger">{{ $errors->first('initial') }}</p>
                                </div>

                           
                            </div>
                            <div class="card-footer text-left">
                                <button class="btn btn-primary">Create</button>
                                <a href="{{ URL::previous() }}" class="btn btn-danger ml-2">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
