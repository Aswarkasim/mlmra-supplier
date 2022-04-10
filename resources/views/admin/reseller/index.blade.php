@extends('adminLayout.master')
@section('title')
    <title>Admin Reseller</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Admin Reseller</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="row mt-3 ml-2 mb-4">
                            <div class="col-md-10">
                                <form action="" method="GET" class="form-inline mr-auto">
                                    <div class="search-element">
                                        <input class="form-control" type="search" name="q" placeholder="Search" aria-label="Search" data-width="250">
                                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                        <div class="search-backdrop"></div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.reseller.reports') }}" class="btn btn-primary">Cetak Laporan</a>
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
                                        <th>No HP</th>
                                        <th>Point</th>
                                        <th>Komisi</th>
                                        <th>Wa</th>
                                        <th>Status</th>
                                        @if(\Illuminate\Support\Facades\Auth::user()->isAdmin)
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                    @foreach($listReseller as $reseller)
                                        <tr>
                                            <td>{{ $reseller->username }}</td>
                                            <td>{{ $reseller->full_name }}</td>
                                            <td>{{ $reseller->phone_number }}</td>
                                            <td>{{ $reseller->point }}</td>
                                            <td>{{ $reseller->commision_total }}</td>
                                            <td>{{ $reseller->whatsapp ?: 'No' }}</td>
                                            <td>{{ $reseller->status }}</td>
                                            @if(\Illuminate\Support\Facades\Auth::user()->isAdmin)
                                            <td>
                                                <a href="{{ route('admin.reseller.edit', $reseller->id) }}" class="btn btn-primary">Edit</a>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    @forelse($listReseller as $reseller)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Data</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $listReseller->total() }}</b></p>
                            {{ $listReseller->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

