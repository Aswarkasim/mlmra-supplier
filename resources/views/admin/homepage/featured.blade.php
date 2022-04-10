@extends('adminLayout.master')
@section('title')
    <title>Admin Featured</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Admin Featured</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="row mt-3 ml-2 mb-4">
                            <div class="col-md-8">
                                <a href="{{ route('admin.featured.create') }}" class="btn btn-primary">Tambah Fitur</a>
                                <a href="{{ route('admin.featured.banner.edit') }}" class="btn btn-primary">Edit Gambar Banner</a>
                            </div>
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
                                <table class="table table-striped table-md ml-2">
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($listFeatured as $featured)
                                        <tr>
                                            <td>{{ $featured->title }}</td>
                                            <td>{!! substr($featured->description_1, 0, 100) !!}</td>
                                            <td>{{ $featured->status }}</td>
                                            <td>
                                                <a href="{{ route('admin.featured.edit', $featured->id) }}" class="btn btn-primary">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @forelse($listFeatured as $featured)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Fitur</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $listFeatured->total() }}</b></p>
                            {{ $listFeatured->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

