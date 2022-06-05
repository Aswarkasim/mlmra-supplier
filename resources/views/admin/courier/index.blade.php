@extends('adminLayout.master')
@section('title')
    <title>Admin Coupon</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Admin Coupon</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="row mt-3 ml-2 mb-4">
                            <div class="col-md-8">
                                <a href="/admin/courier/create" class="btn btn-primary">Tambah Kurir</a>
                            </div>
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
                                <table class="table table-striped table-md ml-3">
                                    <tr>
                                        <th>Nama Kurir</th>
                                        <th>Inisial</th>
                                    </tr>
                                    @foreach($couriers as $courier)
                                        <tr>
                                            <td>{{ $courier->name }}</td>
                                            <td>{{ $courier->initial }}</td>
                                            <td>
                                                <a href="/admin/courier/{{$courier->id}}/edit" class="btn btn-primary">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @forelse($couriers as $courier)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Courier</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $couriers->total() }}</b></p>
                            {{ $couriers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

