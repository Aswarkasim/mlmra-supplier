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
                                <a href="{{ route('admin.coupon.create') }}" class="btn btn-primary">Tambah Kupon</a>
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
                                        <th>Nama Kupon</th>
                                        <th>Syarat & Ketentuan</th>
                                        <th>Potongan Harga</th>
                                        <th>Minimal Point</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($coupons as $coupon)
                                        <tr>
                                            <td>{{ $coupon->name }}</td>
                                            <td>{!! $coupon->term_and_conditions !!}</td>
                                            <td>{{ $coupon->price_cut }}</td>
                                            <td>{{ $coupon->min_point }}</td>
                                            <td>{{ $coupon->status }}</td>
                                            <td>
                                                <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-primary">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @forelse($coupons as $coupon)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Coupon</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $coupons->total() }}</b></p>
                            {{ $coupons->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

