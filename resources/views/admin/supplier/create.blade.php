@extends('adminLayout.master')
@section('title')
    <title>Supplier</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Supplier</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.supplier.save') }}" name="form-product" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Username</label>
                                    <input type="text" class="form-control" name="username" id="title" placeholder="Masukan username" required="">
                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="stok">Nama Lengkap</label>
                                    <input type="number" class="form-control" name="full_name" id="stok" placeholder="Masukan stok" required="">
                                    <p class="text-danger">{{ $errors->first('stock') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="rp_price">Nomor HP</label>
                                    <input type="text" class="form-control" name="phone_number" id="rp_price" placeholder="Masukan harga reseller" required="">
                                    <p class="text-danger">{{ $errors->first('reseller_price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="rp_price">Email</label>
                                    <input type="text" class="form-control" name="email" id="rp_price" placeholder="Masukan harga reseller" required="">
                                    <p class="text-danger">{{ $errors->first('reseller_price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="cp_price">Whatsapp</label>
                                    <input type="text" class="form-control" name="whatsapp" id="cp_price" placeholder="Masukan harga customer" required="">
                                    <p class="text-danger">{{ $errors->first('customer_price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="discount">Password</label>
                                    <input type="text" class="form-control" name="password" id="discount" placeholder="Masukan diskon" required="">
                                    <p class="text-danger">{{ $errors->first('discount') }}</p>
                                </div>
                            </div>
                            <div class="card-footer text-left">
                                <button class="btn btn-primary">Add</button>
                                <a href="" class="btn btn-danger ml-2">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
