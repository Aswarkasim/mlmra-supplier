@extends('adminLayout.master')
@section('title')
    <title>Create Category</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Category</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.category.save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Category Name</label>
                                    <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" placeholder="Masukan judul">
                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="category">Category Type</label>
                                    <select class="form-control" name="category" id="category"/>
                                    @foreach($listCategoryType as $categoryType)
                                        <option value="{{ $categoryType }}" {{ (old("category") == $categoryType ? "selected":"") }}>{{ ucwords($categoryType) }}</option>
                                    @endforeach
                                    </select>
                                        <p class="text-danger">{{ $errors->first('category') }}</p>
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
