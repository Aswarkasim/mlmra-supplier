@extends('adminLayout.master')
@section('title')
    <title>Admin Category</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Admin Category</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="row mt-3 ml-2 mb-4">
                            <div class="col-md-8">
                                <a href="{{ route('admin.category.create') }}" class="btn btn-primary">Tambah Category</a>
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
                                        <th>Name</th>
                                        <th>Tipe Category</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($listCategory as $category)
                                        <tr>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->category_type }}</td>
                                            <td>{{ $category->status }}</td>
                                            <td>
                                                @if(\Illuminate\Support\Facades\Auth::user()->isAdmin)
                                                    <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-primary">Edit</a>
                                                @else
                                                    <a href="" class="btn btn-primary">{{ $category->status != \App\Enums\StatusType::PUBLISHED ? 'PUBLIKASI ADMIN' : 'PUBLIKASI' }}</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @forelse($listCategory as $category)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Category</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $listCategory->total() }}</b></p>
                            {{ $listCategory->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

