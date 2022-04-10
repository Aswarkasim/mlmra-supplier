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
                        <form action="{{ route('admin.coupon.save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Nama Kupon</label>
                                    <input type="text" class="form-control" name="coupon_name" id="title" value="{{ old('coupon_name') }}" placeholder="Masukan nama kupon">
                                    <p class="text-danger">{{ $errors->first('coupon_name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="description">Syarat & Ketentuan</label>
                                    <textarea class="form-control js-summernote" id="description" name="sk"></textarea>
                                    <p class="text-danger">{{ $errors->first('sk') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="title">Potongan Harga</label>
                                    <input type="number" class="form-control" name="price" id="title" value="{{ old('price') }}" placeholder="Masukan potongan harga">
                                    <p class="text-danger">{{ $errors->first('price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="title">Minimal Point</label>
                                    <input type="number" class="form-control" name="point" id="title" value="{{ old('point') }}" placeholder="Masukan minimal point">
                                    <p class="text-danger">{{ $errors->first('point') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="category">Status</label>
                                    <select class="form-control" name="status" id="category"/>
                                        <option value="{{ \App\Enums\StatusType::ACTIVE }}">{{ \App\Enums\StatusType::ACTIVE }}</option>
                                        <option value="{{ \App\Enums\StatusType::INACTIVE }}">{{ \App\Enums\StatusType::INACTIVE }}</option>
                                    </select>
                                        <p class="text-danger">{{ $errors->first('status') }}</p>
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
