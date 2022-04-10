@extends('adminLayout.master')
@section('title')
    <title>Edit Profit</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Profit</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.keuntungan.update', $keuntungan->id) }}" name="form-profit" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="action_type"/>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $keuntungan->title) }}" id="title" placeholder="Masukan judul" required="">
                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control js-summernote" id="description" name="description_1">{{ old('description_1', $keuntungan->description_1) }}</textarea>
                                    <p class="text-danger">{{ $errors->first('description_1') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Gambar</label> <br>
                                    <img src="{{ $keuntungan->media ? asset('storage/'.$keuntungan->media->file_name) : '' }}" height="120" width="180">
                                    <div class="content">
                                        Preview
                                    </div>
                                    <input type="file" class="form-control" name="thumbnail">
                                    <p class="text-danger">{{ $errors->first('thumbnail') }}</p>
                                </div>
                            </div>
                            <div class="box-footer mb-3 ml-4">
                                <a href="{{ route('admin.keuntungan') }}" class="btn btn-info margin-r-5">CANCEL</a>
                                @if($keuntungan->status == \App\Enums\StatusType::DRAFT() || $keuntungan->status == \App\Enums\StatusType::ARCHIVED())
                                    <button type="button" class="btn btn-primary margin-r-5 js-profit-action js-action-btn" data-action-type="SAVE">SAVE</button>
                                @endif
                                @if($keuntungan->status == \App\Enums\StatusType::DRAFT() || $keuntungan->status == \App\Enums\StatusType::PUBLISHED())
                                    <button type="button" class="btn btn-primary margin-r-5 js-profit-action js-action-btn" data-action-type="PUBLISH">PUBLISH</button>
                                @endif
                                @if($keuntungan->status != \App\Enums\StatusType::ARCHIVED())
                                    <button type="button" class="btn btn-warning margin-r-5 js-profit-action js-action-btn" data-action-type="ARCHIVE">ARCHIVE</button>
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
    <script src="{{ asset('admin/asset/js/profit.js') }}"></script>
@endpush
