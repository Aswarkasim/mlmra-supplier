@extends('adminLayout.master')
@section('title')
    <title>Edit Profil</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Profil</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.profile.update', $account->id) }}" name="form-profile" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" name="username" value="{{ old('username', $account->username) }}" id="username" placeholder="Masukan username">
                                    <p class="text-danger">{{ $errors->first('username') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="full_name" value="{{ old('full_name', $account->full_name) }}" id="name" placeholder="Masukan Nama Lengkap">
                                    <p class="text-danger">{{ $errors->first('full_name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="hp">No HP</label>
                                    <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number', $account->phone_number) }}" id="hp" placeholder="Masukan No Hp">
                                    <p class="text-danger">{{ $errors->first('phone_number') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email', $account->email) }}" id="email" placeholder="Masukan Email">
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="wa">Whatsapp</label>
                                    <input type="text" class="form-control" name="whatsapp" value="{{ old('whatsapp', $account->whatsapp) }}" id="wa" placeholder="Masukan WA">
                                    <p class="text-danger">{{ $errors->first('whatsapp') }}</p>
                                </div>
                                <div class="form-group mb-0">
                                    <input type="checkbox" id="ps" name="opsi_password" @if(old('opsi_password')) checked @endif>
                                    <label for="ps">Ganti Password</label>
                                </div>
                                <div class="form-group js-opsi-password {{ old('opsi_password') ? '' : 'd-none'}}">
                                    <label for="pass">Password</label>
                                    <p class="text-danger mt-0 mb-0">Note: Jangan diisi apabila tidak ingin mengganti password</p>
                                    <input type="password" class="form-control js-password-val" name="password" id="pass" placeholder="Masukkan Password" data-rule-required="true"
                                           data-msg-required="Required field">
                                    <p class="text-danger">{{ $errors->first('password') }}</p>
                                </div>
                                <div class="form-group js-opsi-password {{ old('opsi_password') ? '' : 'd-none'}}">
                                    <label for="passp">Konfirmasi Password</label>
                                    <input type="password" class="form-control js-password-val" name="password_confirmation" id="passp" placeholder="Masukkan Password" data-rule-required="true"
                                           data-msg-required="Required field">
                                    <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Thumbnail</label> <br>
                                    <img src="{{ $account->media ? asset('storage/'.$account->media->file_name) : '' }}" height="120" width="180">
                                    <div class="content">
                                        Preview
                                    </div>
                                    <input type="file" class="form-control" name="image">
                                    <p class="text-danger">{{ $errors->first('image') }}</p>
                                </div>
                            </div>
                            <div class="box-footer mb-3 ml-4">
                                <button type="submit" class="btn btn-primary margin-r-5">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="{{ asset('public/admin/asset/js/profile.js') }}"></script>
@endpush
