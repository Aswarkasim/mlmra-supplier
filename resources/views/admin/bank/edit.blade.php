@extends('adminLayout.master')
@section('title')
    <title>Edit Bank Account</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Bank Account</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.bankAccount.update', $bankAccount->id) }}" name="form-bank-account" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="bank">Nama Bank</label>
                                    <select class="form-control" name="bank_name" id="bank" placeholder="Masukan Nama Bank">
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank }}" {{ $bankAccount->bank_name == $bank || old('bank_name') == $bank ? "selected":"" }}>{{ $bank }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('bank_name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="number">Nomor Akun</label>
                                    <input type="text" class="form-control" name="account_number" value="{{ old('account_number', $bankAccount->account_number) }}" id="number" placeholder="Masukan Nomor Akun">
                                    <p class="text-danger">{{ $errors->first('account_number') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="name_account">Nama Akun</label>
                                    <input type="text" class="form-control" name="account_name" value="{{ old('account_name', $bankAccount->account_name) }}" id="name_account" placeholder="Masukan Nama Lengkap">
                                    <p class="text-danger">{{ $errors->first('account_name') }}</p>
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
