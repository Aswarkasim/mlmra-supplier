@extends('adminLayout.master')
@section('title')
    <title>Admin Product</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Admin Product</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="row mt-3 ml-2 mb-4">
                            <div class="col-md-{{ \Illuminate\Support\Facades\Auth::user()->isAdmin ? '4' : '8' }}">
                                <a href="{{ route('admin.product.create') }}" class="btn btn-primary">Tambah Product</a>
                            </div>
                            @if(\Illuminate\Support\Facades\Auth::user()->isAdmin)
                            <div class="col-md-4">
                                <div class="col-md-12">
                                    <a href="{{ route('admin.product') }}" class="btn btn-primary">Supplier</a>
                                    <a href="{{ route('admin.product.asadmin') }}" class="btn btn-primary">Admin</a>
                                </div>
                            </div>
                            @endif
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
                                        @if(\Illuminate\Support\Facades\Auth::user()->isAdmin && $modeAdmin)
                                        <th>Supplier</th>
                                        @endif
                                        <th>Title</th>
                                        <th>Stok</th>
                                        <th>Harga Reseller</th>
                                        <th>Harga Customer</th>
                                        <th>Harga Katalog</th>
                                        <th>Diskon (Rp)</th>
                                        <th>Komisi (Rp)</th>
                                        <th>Poin / Produk</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($listProduct as $product)
                                        <tr>
                                            @if(\Illuminate\Support\Facades\Auth::user()->isAdmin && $modeAdmin)
                                                <td>{{ $product->user->username }}</td>
                                            @endif
                                            <td>{{ $product->title }}</td>
                                            <td>{{ $product->stock }}</td>
                                            <td>{{ number_format($product->reseller_price) }}</td>
                                            <td>{{ number_format($product->customer_price) }}</td>
                                            <td>{{ number_format($product->catalog_price) }}</td>
                                            <td>{{ number_format($product->discount) }}</td>
                                            <td>{{ $product->commision_rp ? number_format($product->commision_rp) : '-' }}</td>
                                            <td>{{ $product->point }} / {{ $product->point_product }}</td>
                                            <td>{{ $product->category->name }}</td>
                                            <td>{{ $product->status }}</td>
                                            <td>
                                                <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @forelse($listProduct as $product)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Product</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $listProduct->total() }}</b></p>
                            {{ $listProduct->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

