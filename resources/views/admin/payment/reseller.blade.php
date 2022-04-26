@extends('adminLayout.master')
@section('title')
    <title>Admin Payment</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Admin Payment</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="row mt-3 ml-2 mb-4">
                            <div class="col-md-4">
                                <form action="" method="GET" class="form-inline mr-auto">
                                    <div class="search-element">
                                        <input class="form-control" type="search" name="search" placeholder="Search" aria-label="Search" data-width="250">
                                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                        <div class="search-backdrop"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @if($message = session('message'))
                            <div class="row mt-0 ml-2">
                                <div class="col-md-6">
                                    <h4 class="btn btn-danger">{{ $message }}</h4>
                                </div>
                            </div>
                        @endif
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <style>
                                    .bg-confirmation{
                                        background-color: aqua;

                                    }
                                </style>
                                
                                <table class="table  table-md">
                                    <tr>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($listResellerPayment as $payment)
                                        {{-- <tr class="{{ $payment->status == \App\Enums\PaymentStatus::CONFIRMATION ? 'bg-confirm' : '' }}"> --}}
                                        <tr class=" @if ($payment->status == \App\Enums\PaymentStatus::CONFIRMATION)
                                            {{ 'bg-confirmation' }}
                                        @endif">
                                            <td>{{ $payment->resellerTransaction->reseller->username }}</td>
                                            <td>{{ $payment->resellerTransaction->reseller->full_name }}</td>
                                            <td>{{ $payment->status }}</td>
                                            <td>
                                                @if(\Illuminate\Support\Facades\Auth::user()->isAdmin)
                                                    <a href="{{ route('admin.confirm.payment', $payment->id) }}" class="btn btn-primary">Konfirmasi Pembayaran Admin</a>
                                                @else
                                                    <p class="btn btn-outline-primary">Tunggu Konfirmasi Admin</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @forelse($listResellerPayment as $payment)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Data</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $listResellerPayment->total() }}</b></p>
                            {{ $listResellerPayment->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

