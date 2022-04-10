@extends('adminLayout.master')
@section('title')
    <title>Admin Customer Transaction</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Admin Customer Transaction</h1>
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
                                <table class="table table-striped table-md">
                                    <tr>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($listCustomerTransaction as $transaction)
                                        <tr>
                                            <td>{{ $transaction->customer->username }}</td>
                                            <td>{{ $transaction->customer->full_name }}</td>
                                            <td>{{ $transaction->status }}</td>
                                            <td>
                                                <a href="" class="btn btn-primary">Payment Confirm</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @forelse($listCustomerTransaction as $transaction)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Data</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $listCustomerTransaction->total() }}</b></p>
                            {{ $listCustomerTransaction->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

