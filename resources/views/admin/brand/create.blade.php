@extends('adminLayout.master')
@section('title')
    <title>Create Brand</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Brand</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.brand.save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Brand Name</label>
                                    <input type="text" class="form-control" name="brand_name" id="title" value="{{ old('brand_name') }}" placeholder="Masukan nama brand">
                                    <p class="text-danger">{{ $errors->first('brand_name') }}</p>
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
