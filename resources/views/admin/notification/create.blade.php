@extends('adminLayout.master')
@section('title')
    <title>Create Notification</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Notification</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.notification.save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" placeholder="Masukan judul">
                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description (*)</label>
                                    <textarea class="form-control js-summernote" id="description" name="description"></textarea>
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="category">Jenis Notifikasi</label>
                                    <select class="form-control" name="notification_type" id="category"/>
                                    @foreach($listNotificationType as $notificationType)
                                        <option value="{{ $notificationType }}" {{ (old("notification_type") == $notificationType ? "selected":"") }}>{{ ucwords($notificationType) }}</option>
                                    @endforeach
                                    </select>
                                        <p class="text-danger">{{ $errors->first('notification_type') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Thumbnail</label>
                                    <input type="file" class="form-control" name="thumbnail"d>
                                    <p class="text-danger">{{ $errors->first('thumbnail') }}</p>
                                </div>
                            </div>
                            <div class="card-footer text-left">
                                <button class="btn btn-primary">Create</button>
                                <a href="{{ URL::previous() }}" class="btn btn-danger ml-2">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
