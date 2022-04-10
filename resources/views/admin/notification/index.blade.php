@extends('adminLayout.master')
@section('title')
    <title>Admin Notification</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Admin Notification</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="row mt-3 ml-2 mb-4">
                            <div class="col-md-8">
                                <a href="{{ route('admin.notification.create') }}" class="btn btn-primary">Tambah Pemberitahuan</a>
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
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <tH>Jenis Notifikasi</tH>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($listNotification as $notification)
                                        <tr>
                                            <td>{{ $notification->title }}</td>
                                            <td>{{ $notification->description }}</td>
                                            <td>{{ $notification->notification_type }}</td>
                                            <td>{{ $notification->status }}</td>
                                            <td>
                                                <a href="{{ route('admin.notification.edit', $notification->id) }}" class="btn btn-primary">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @forelse($listNotification as $notification)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Notification</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $listNotification->total() }}</b></p>
                            {{ $listNotification->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

