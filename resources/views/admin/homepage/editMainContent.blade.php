@extends('adminLayout.master')
@section('title')
    <title>Edit Main Content</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Main Content</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.mainContent.update') }}" name="form-main-content" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <p><b>(*) Wajib Diisi</b></p>
                                <div class="form-group">
                                    <label for="title">Title (*)</label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $mainContent->title) }}" id="title" placeholder="Masukan judul">
                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="button_text">Button Text (*)</label>
                                    <input type="text" class="form-control" name="button_text" value="{{ old('button_text', $mainContent->button_text) }}" id="button_text" placeholder="Masukan Teks Button">
                                    <p class="text-danger">{{ $errors->first('button_text') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="description_1">Description 1</label>
                                    <textarea class="form-control js-summernote" id="description_1" name="description_1">{{ old('description_1', $mainContent->description_1) }}</textarea>
                                    <p class="text-danger">{{ $errors->first('description_1') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="description_2">Description 2</label>
                                    <textarea class="form-control js-summernote" id="description_2" name="description_2">{{ old('description_2', $mainContent->description_2) }}</textarea>
                                    <p class="text-danger">{{ $errors->first('description_2') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Gambar (*)</label> <br>
                                    <img src="{{ $mainContent->media ? asset('storage/'.$mainContent->media->file_name) : '' }}" height="120" width="180">
                                    <div class="content">
                                        Preview
                                    </div>
                                    <input type="file" class="form-control" name="image">
                                    <p class="text-danger">{{ $errors->first('image') }}</p>
                                </div>
                            </div>
                            <div class="box-footer mb-3 ml-4">
                                <button type="submit" class="btn btn-primary margin-r-5">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
