@extends('adminLayout.master')
@section('title')
    <title>Edit Brand</title>
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
                        <form action="{{ route('admin.brand.update', $brand->id) }}" name="form-brand" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="action_type"/>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Brand Name</label>
                                    <input type="text" class="form-control" name="brand_name" value="{{ old('brand_name', $brand->name) }}" id="title" placeholder="Masukan nama brand">
                                    <p class="text-danger">{{ $errors->first('brand_name') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Thumbnail</label> <br>
                                    <img src="{{ $brand->media ? asset('storage/'.$brand->media->file_name) : '' }}" height="120" width="180">
                                    <div class="content">
                                        Preview
                                    </div>
                                    <input type="file" class="form-control" name="thumbnail">
                                    <p class="text-danger">{{ $errors->first('thumbnail') }}</p>
                                </div>
                            </div>
                            <div class="box-footer mb-3 ml-4">
                                <a href="{{ route('admin.category') }}" class="btn btn-info margin-r-5">CANCEL</a>
                                @if($brand->status == \App\Enums\StatusType::DRAFT() || $brand->status == \App\Enums\StatusType::ARCHIVED())
                                    <button type="button" class="btn btn-primary margin-r-5 js-brand-action js-action-btn" data-action-type="SAVE">SAVE</button>
                                @endif
                                @if($brand->status == \App\Enums\StatusType::DRAFT() || $brand->status == \App\Enums\StatusType::PUBLISHED())
                                    <button type="button" class="btn btn-primary margin-r-5 js-brand-action js-action-btn" data-action-type="PUBLISH">PUBLISH</button>
                                @endif
                                @if($brand->status != \App\Enums\StatusType::ARCHIVED())
                                    <button type="button" class="btn btn-warning margin-r-5 js-brand-action js-action-btn" data-action-type="ARCHIVE">ARCHIVE</button>
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
    <script src="{{ asset('public/admin/asset/js/brand.js') }}"></script>
@endpush

