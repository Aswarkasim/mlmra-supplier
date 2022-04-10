@extends('adminLayout.master')
@section('title')
    <title>Create Sub Category</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Sub Category</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.subcategory.save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Sub Category Name</label>
                                    <input type="text" class="form-control" name="name" id="title" value="{{ old('name') }}" placeholder="Masukan judul">
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="category">Category Type</label>
                                    <select class="form-control" name="category" id="category"/>
                                    @foreach($listSubCategoryType as $subCategoryType)
                                        <option value="{{ $subCategoryType->id }}" {{ (old("category") == $subCategoryType->id ? "selected":"") }}>{{ $subCategoryType->name }}</option>
                                    @endforeach
                                    </select>
                                        <p class="text-danger">{{ $errors->first('category') }}</p>
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
