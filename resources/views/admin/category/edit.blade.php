@extends('adminLayout.master')
@section('title')
    <title>Edit Category</title>
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
                        <form action="{{ route('admin.category.update', $category->id) }}" name="form-category" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="action_type"/>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Category Name</label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $category->name) }}" id="title" placeholder="Masukan judul">
                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="level">Category Type</label>
                                    <select class="form-control js-select2-no-search" name="category" id="level">
                                        @foreach($listCategoryType as $level)
                                            <option value="{{$level}}" {{ $category->category_type == $level || old('category') == $level ? 'selected' : '' }}>{{$level}}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('category') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Thumbnail</label> <br>
                                    <img src="{{ $category->media ? asset('storage/app/public/'.$category->media->file_name) : '' }}" height="120" width="180">
                                    <div class="content">
                                        Preview
                                    </div>
                                    <input type="file" class="form-control" name="thumbnail">
                                    <p class="text-danger">{{ $errors->first('thumbnail') }}</p>
                                </div>
                            </div>
                            <div class="box-footer mb-3 ml-4">
                                <a href="{{ route('admin.category') }}" class="btn btn-info margin-r-5">CANCEL</a>
                                @if($category->status == \App\Enums\StatusType::DRAFT() || $category->status == \App\Enums\StatusType::ARCHIVED())
                                    <button type="button" class="btn btn-primary margin-r-5 js-category-action js-action-btn" data-action-type="SAVE">SAVE</button>
                                @endif
                                @if($category->status == \App\Enums\StatusType::DRAFT() || $category->status == \App\Enums\StatusType::PUBLISHED())
                                    <button type="button" class="btn btn-primary margin-r-5 js-category-action js-action-btn" data-action-type="PUBLISH">PUBLISH</button>
                                @endif
                                @if($category->status != \App\Enums\StatusType::ARCHIVED())
                                    <button type="button" class="btn btn-warning margin-r-5 js-category-action js-action-btn" data-action-type="ARCHIVE">ARCHIVE</button>
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
    <script src="{{ asset('public/admin/asset/js/category.js') }}"></script>
@endpush

