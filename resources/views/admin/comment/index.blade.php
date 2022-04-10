@extends('adminLayout.master')
@section('title')
    <title>Admin Komentar</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Admin Komentar</h1>
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
                                        <th>Reseller</th>
                                        <th>Produk</th>
                                        <th>Comment</th>
                                        <th>Rating</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($listComment as $comment)
                                        <tr>
                                            <td><a href="{{ route('admin.reseller.edit', $comment->reseller->id) }}">{{ $comment->reseller->full_name }}</a></td>
                                            <td><a href="{{ route('admin.product.edit', $comment->product->id) }}">{{ $comment->product->title }}</a></td>
                                            <td>{{ $comment->comment }}</td>
                                            <td>{{ $comment->rating }}</td>
                                            <td class="{{ $comment->status == \App\Enums\StatusType::BLOCKED ? 'text-danger' : '' }}">{{ $comment->status }}</td>
                                            <td>
                                                <a href="{{ route('admin.comment.block', $comment->id) }}" class="btn btn-primary" onclick="return confirm('Yakin mau diblokir?')">Block</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @forelse($listComment as $comment)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Data</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $listComment->total() }}</b></p>
                            {{ $listComment->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

