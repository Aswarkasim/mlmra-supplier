@extends('adminLayout.master')
@section('title')
<title>Edit Featured Banner</title>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Featured Banner</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col">
                <div class="card">
                    <form action="{{ route('admin.featured.banner.update') }}" name="form-featured-banner" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action_type"/>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Banner</label> <br>
                                <img src="{{ $featuredBanner->media ? asset('storage/'.$featuredBanner->media->file_name) : '' }}" height="120" width="180">
                                <div class="content">
                                    Preview
                                </div>
                                <input type="file" class="form-control" name="thumbnail">
                                <p class="text-danger">{{ $errors->first('thumbnail') }}</p>
                            </div>
                        </div>
                        <div class="box-footer mb-3 ml-4">
                            <a href="{{ route('admin.featured') }}" class="btn btn-info margin-r-5">CANCEL</a>
                            @if($featuredBanner->status == \App\Enums\StatusType::DRAFT() || $featuredBanner->status == \App\Enums\StatusType::ARCHIVED())
                            <button type="button" class="btn btn-primary margin-r-5 js-featured-banner-action js-action-btn" data-action-type="SAVE">SAVE</button>
                            @endif
                            @if($featuredBanner->status == \App\Enums\StatusType::DRAFT() || $featuredBanner->status == \App\Enums\StatusType::PUBLISHED())
                            <button type="button" class="btn btn-primary margin-r-5 js-featured-banner-action js-action-btn" data-action-type="PUBLISH">PUBLISH</button>
                            @endif
                            @if($featuredBanner->status != \App\Enums\StatusType::ARCHIVED())
                            <button type="button" class="btn btn-warning margin-r-5 js-featured-banner-action js-action-btn" data-action-type="ARCHIVE">ARCHIVE</button>
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
<script src="{{ asset('admin/asset/js/featured.js') }}"></script>
@endpush
