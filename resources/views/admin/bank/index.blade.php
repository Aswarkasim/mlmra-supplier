@extends('adminLayout.master')
@section('title')
    <title>Admin Bank Account</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Admin Bank Account</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="row mt-3 ml-2 mb-4">
                            <div class="col-md-{{ \Illuminate\Support\Facades\Auth::user()->isAdmin ? '4' : '8' }}">
                                <a href="{{ route('admin.bankAccount.create') }}" class="btn btn-primary">Tambah Bank Account</a>
                            </div>
                            @if(\Illuminate\Support\Facades\Auth::user()->isAdmin)
                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <a href="{{ route('admin.bankAccount') }}" class="btn btn-primary">Supplier</a>
                                        <a href="{{ route('admin.bankAccount.asadmin') }}" class="btn btn-primary">Admin</a>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4">
                                <form action="{{ route('admin.bankAccount') }}" method="GET" class="form-inline mr-auto">
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
                                        <th>Nama Akun</th>
                                        <th>Nomor Akun</th>
                                        <th>Nama Bank</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($listBankAccount as $bankAccount)
                                        <tr>
                                            <td>{{ $bankAccount->account_name }}</td>
                                            <td>{{ $bankAccount->account_number }}</td>
                                            <td>{{ $bankAccount->bank_name }}</td>
                                            <td>
                                                <a href="{{ route('admin.bankAccount.edit', $bankAccount->id) }}" class="btn btn-primary">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @forelse($listBankAccount as $bankAccount)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Bank Account</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $listBankAccount->total() }}</b></p>
                            {{ $listBankAccount->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

