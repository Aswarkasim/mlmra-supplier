@extends('adminLayout.master')
@section('title')
    <title>Edit Notification</title>
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
                        <form action="{{ route('admin.notification.update', $notification->id) }}" name="form-notification" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="action_type"/>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Judul</label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $notification->name) }}" id="title" placeholder="Masukan judul">
                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control js-summernote" id="description" name="description">{{ $product->description }}</textarea>
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="level">Jenis Notifikasi</label>
                                    <select class="form-control js-select2-no-search" name="notification_type" id="level">
                                        @foreach($listNotificationType as $notificationType)
                                            <option value="{{$notificationType}}" {{ $notification->notification_type == $notificationType || old('notification_type') == $notificationType ? 'selected' : '' }}>{{$notificationType}}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('notification_type') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Thumbnail</label> <br>
                                    <img src="{{ $notification->media ? asset('storage/'.$notification->media->file_name) : '' }}" height="120" width="180">
                                    <div class="content">
                                        Preview
                                    </div>
                                    <input type="file" class="form-control" name="thumbnail">
                                    <p class="text-danger">{{ $errors->first('thumbnail') }}</p>
                                </div>
                            </div>
                            <div class="box-footer mb-3 ml-4">
                                <a href="{{ route('admin.category') }}" class="btn btn-info margin-r-5">CANCEL</a>
                                @if($notification->status == \App\Enums\StatusType::DRAFT() || $notification->status == \App\Enums\StatusType::ARCHIVED())
                                    <button type="button" class="btn btn-primary margin-r-5 js-notification-action js-action-btn" data-action-type="SAVE">SAVE</button>
                                @endif
                                @if($notification->status == \App\Enums\StatusType::DRAFT() || $notification->status == \App\Enums\StatusType::PUBLISHED())
                                    <button type="button" class="btn btn-primary margin-r-5 js-notification-action js-action-btn" data-action-type="PUBLISH">PUBLISH</button>
                                @endif
                                @if($notification->status != \App\Enums\StatusType::ARCHIVED())
                                    <button type="button" class="btn btn-warning margin-r-5 js-notification-action js-action-btn" data-action-type="ARCHIVE">ARCHIVE</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="{{ asset('public/admin/asset/js/notification.js') }}"></script>
@endpush

