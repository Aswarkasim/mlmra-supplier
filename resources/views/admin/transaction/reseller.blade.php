@extends('adminLayout.master')
@section('title')
    <title>Admin Reseller Transaction</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Admin Reseller Transaction</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="row mt-3 ml-2 mb-4">
                            <div class="col-md-4">
                                <form action="" method="GET" class="form-inline mr-auto">
                                    <div class="search-element">
                                        <input class="form-control" type="search" name="q" placeholder="Search" aria-label="Search" data-width="250">
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
                                <table class="table table-striped table-md">
                                    <tr>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Status Transaksi</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($listResellerTransaction as $transaction)
                                        <tr>
                                            <td>{{ $transaction->reseller->username }}</td>
                                            <td>{{ $transaction->reseller->full_name }}</td>
                                            <td>{{ $transaction->transaction_status }}</td>
                                            <td>
                                                @if($transaction->transaction_status == \App\Enums\TransactionStatus::UNPAID)
                                                    <a href="{{ route('admin.confirmation', ['id' => $transaction->id, 'status' => $transaction->transaction_status]) }}" class="btn btn-primary">Konfirmasi Pembayaran Admin</a>
                                                @elseif($transaction->transaction_status == \App\Enums\TransactionStatus::PROCESS)
                                                    <button type="button" class="btn btn-primary text-right mb-2" id="modal-transaction" data-id="{{ $transaction->id }}" data-toggle="modal" data-target="#exampleModal5">
                                                        Konfirmasi Produk Reseller
                                                    </button>
                                                @elseif($transaction->transaction_status == \App\Enums\TransactionStatus::SENT)
                                                    <a href="{{ route('admin.confirmation', ['id' => $transaction->id, 'status' => $transaction->transaction_status]) }}" class="btn btn-primary">Ubah Status</a>
                                                @elseif($transaction->transaction_status == \App\Enums\TransactionStatus::DONE)
                                                    <a href="{{ route('admin.confirmation', ['id' => $transaction->id, 'status' => $transaction->transaction_status]) }}" class="btn btn-primary">Selesai</a>
                                                @elseif($transaction->transaction_status == \App\Enums\TransactionStatus::CANCEL)
                                                    <a href="{{ route('admin.confirmation', ['id' => $transaction->id, 'status' => $transaction->transaction_status]) }}" class="btn btn-primary">Selesai</a>
                                                @elseif($transaction->transaction_status == \App\Enums\TransactionStatus::RETURNED)
                                                    <a href="{{ route('admin.confirmation', ['id' => $transaction->id, 'status' => $transaction->transaction_status]) }}" class="btn btn-primary">Selesai</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @forelse($listResellerTransaction as $transaction)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Data</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $listResellerTransaction->total() }}</b></p>
                            {{ $listResellerTransaction->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="{{ asset('public/admin/asset/js/transaction.js') }}"></script>
@endpush

<!-- Modal -->
<div class="modal fade" id="exampleModal5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ubah Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('process.confirmation') }}" name="form-product" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" name="transactionId" id="transaction-id">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" id="status">
                                <option value="{{ \App\Enums\TransactionStatus::SENT }}">{{ \App\Enums\TransactionStatus::SENT }}</option>
                                <option value="{{ \App\Enums\TransactionStatus::CANCEL }}">{{ \App\Enums\TransactionStatus::CANCEL }}</option>
                                <option value="{{ \App\Enums\TransactionStatus::RETURNED }}">{{ \App\Enums\TransactionStatus::RETURNED }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

