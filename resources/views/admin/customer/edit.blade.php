@extends('adminLayout.master')
@section('username')
    <username>Edit Customer</username>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Customer</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.customer.update', $customer->id) }}" name="form-supplier" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" name="username" value="{{ old('username', $customer->username) }}" id="username" placeholder="Masukan username">
                                    <p class="text-danger">{{ $errors->first('username') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="full_name" value="{{ old('full_name', $customer->full_name) }}" id="name" placeholder="Masukan Nama Lengkap">
                                    <p class="text-danger">{{ $errors->first('full_name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="hp">No HP</label>
                                    <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}" id="hp" placeholder="Masukan No Hp">
                                    <p class="text-danger">{{ $errors->first('phone_number') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email', $customer->email) }}" id="email" placeholder="Masukan Email">
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="wa">Whatsapp</label>
                                    <input type="text" class="form-control" name="whatsapp" value="{{ old('whatsapp', $customer->whatsapp) }}" id="wa" placeholder="Masukan WA">
                                    <p class="text-danger">{{ $errors->first('whatsapp') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="ACTIVE" {{ $customer->status == 'ACTIVE' || old('status') ==  'ACTIVE' ? "selected" : "" }}>ACTIVE</option>
                                        <option value="INACTIVE" {{ $customer->status == 'INACTIVE' || old('status') ==  'INACTIVE' ? "selected" : "" }}>INACTIVE</option>
                                    </select>
                                    <p class="text-danger">{{ $errors->first('status') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Thumbnail</label> <br>
                                    <img src="{{ $customer->media ? asset('storage/'.$customer->media->file_name) : '' }}" height="120" width="180">
                                    <div class="content">
                                        Preview
                                    </div>
                                    <input type="file" class="form-control" name="image">
                                    <p class="text-danger">{{ $errors->first('image') }}</p>
                                </div>
                            </div>
                            <div class="box-footer mb-3 ml-4">
                                <button type="submit" class="btn btn-primary margin-r-5">UPDATE</button>
                                <a href="{{ route('admin.customer') }}" class="btn btn-info margin-r-5">CANCEL</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
