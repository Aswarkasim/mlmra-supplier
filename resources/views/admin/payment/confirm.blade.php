@extends('adminLayout.master')
@section('username')
    <username>Konfirmasi</username>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Konfirmasi</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.update.payment', $payment->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" name="username" value="{{ $payment->resellerTransaction->reseller->username }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="full_name" value="{{ $payment->resellerTransaction->reseller->full_name }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="REJECTED" {{ $payment->status == \App\Enums\PaymentStatus::REJECTED ? "selected" : "" }}>{{ \App\Enums\PaymentStatus::REJECTED }}</option>
                                        <option value="PAID" {{ $payment->status == \App\Enums\PaymentStatus::PAID ? "selected" : "" }}>{{ \App\Enums\PaymentStatus::PAID }}</option>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Bukti Pembayaran</label> <br>
                                    <img src="{{ $payment->media ? asset('/'.$payment->media->path.$payment->media->file_name) : '' }}" height="120" width="180">
                                    <div class="content">
                                        Preview
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer mb-3 ml-4">
                                <button type="submit" class="btn btn-primary margin-r-5">UPDATE</button>
                                <a href="{{ route('admin.resellerPayment') }}" class="btn btn-info margin-r-5">CANCEL</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
