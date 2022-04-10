@extends('adminLayout.master')
@section('title')
    <title>Create Profit</title>
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
                        <form action="{{ route('admin.keuntungan.save') }}" name="form-profit" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title') }}" id="title" placeholder="Masukan judul" required="">
                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control js-summernote" id="description" name="description_1">{{ old('description_1') }}</textarea>
                                    <p class="text-danger">{{ $errors->first('description_1') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Gambar</label> <br>
                                    <div class="content">
                                        Preview
                                    </div>
                                    <input type="file" class="form-control" name="thumbnail">
                                    <p class="text-danger">{{ $errors->first('thumbnail') }}</p>
                                </div>
                            </div>
                            <div class="box-footer mb-3 ml-4">
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
