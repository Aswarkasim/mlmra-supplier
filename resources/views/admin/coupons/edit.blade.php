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
                        <form action="{{ route('admin.coupon.update', $coupon->id) }}" name="form-coupon" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="action_type"/>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Nama Kupon</label>
                                    <input type="text" class="form-control" name="coupon_name" value="{{ old('coupon_name', $coupon->name) }}" id="title" placeholder="Masukan nama kupon">
                                    <p class="text-danger">{{ $errors->first('coupon_name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="description">Syarat & Ketentuan</label>
                                    <textarea class="form-control js-summernote" id="description" name="sk">{{ $coupon->term_and_conditions }}</textarea>
                                    <p class="text-danger">{{ $errors->first('sk') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="title">Potongan Harga</label>
                                    <input type="text" class="form-control" name="price" value="{{ old('price', $coupon->price_cut) }}" id="title" placeholder="Masukan potongan harga">
                                    <p class="text-danger">{{ $errors->first('price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="title">Minimal Point</label>
                                    <input type="text" class="form-control" name="point" value="{{ old('point', $coupon->min_point) }}" id="title" placeholder="Masukan minimal point">
                                    <p class="text-danger">{{ $errors->first('point') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="category">Status</label>
                                    <select class="form-control" name="status" id="category"/>
                                        <option value="{{ \App\Enums\StatusType::ACTIVE }}" {{ $coupon->status == \App\Enums\StatusType::ACTIVE || old('status') == $coupon->status ? 'selected' : '' }}>{{ \App\Enums\StatusType::ACTIVE }}</option>
                                        <option value="{{ \App\Enums\StatusType::INACTIVE }}" {{ $coupon->status == \App\Enums\StatusType::INACTIVE || old('status') == $coupon->status ? 'selected' : '' }}>{{ \App\Enums\StatusType::INACTIVE }}</option>
                                    </select>
                                    <p class="text-danger">{{ $errors->first('status') }}</p>
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

