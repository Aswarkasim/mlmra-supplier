@extends('adminLayout.master')
@section('title')
    <title>Edit Sub Category</title>
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
                        <form action="{{ route('admin.subcategory.update', $subCategory->id) }}" name="form-subcategory" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="action_type"/>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Sub Category Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $subCategory->name) }}" id="title" placeholder="Masukan judul">
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="level">Category</label>
                                    <select class="form-control js-select2-no-search" name="category" id="level">
                                        @foreach($listSubCategoryType as $category)
                                            <option value="{{$category->id}}" {{ $category->id == $subCategory->category_id || old('category') == $subCategory->category_id ? 'selected' : '' }}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('category') }}</p>
                                </div>
                            </div>
                            <div class="box-footer mb-3 ml-4">
                                <a href="{{ route('admin.category') }}" class="btn btn-info margin-r-5">CANCEL</a>
                                @if($subCategory->status == \App\Enums\StatusType::DRAFT() || $category->status == \App\Enums\StatusType::ARCHIVED())
                                    <button type="button" class="btn btn-primary margin-r-5 js-subcategory-action js-action-btn" data-action-type="SAVE">SAVE</button>
                                @endif
                                @if($subCategory->status == \App\Enums\StatusType::DRAFT() || $category->status == \App\Enums\StatusType::PUBLISHED())
                                    <button type="button" class="btn btn-primary margin-r-5 js-subcategory-action js-action-btn" data-action-type="PUBLISH">PUBLISH</button>
                                @endif
                                @if($subCategory->status != \App\Enums\StatusType::ARCHIVED())
                                    <button type="button" class="btn btn-warning margin-r-5 js-subcategory-action js-action-btn" data-action-type="ARCHIVE">ARCHIVE</button>
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
    <script src="{{ asset('public/admin/asset/js/subcategory.js') }}"></script>
@endpush

