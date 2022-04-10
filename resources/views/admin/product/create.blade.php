@extends('adminLayout.master')
@section('title')
    <title>Product</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Product</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.product.save') }}" name="form-product" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <p><b>(*) Wajib diisi</b></p>
                                <div class="form-group">
                                    <label for="title">Title (*)</label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Masukan judul" value="{{ old('title') }}">
                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    <label for="category">Brand</label>--}}
{{--                                    <select class="form-control js-category" name="brand" id="category">--}}
{{--                                        <option value="0">-- pilih brand --</option>--}}
{{--                                        @foreach($listBrand as $brand)--}}
{{--                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                    <p class="text-danger">{{ $errors->first('brand') }}</p>--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <label for="stok">Stok (*)</label>
                                    <input type="number" class="form-control" name="stock" id="stok" placeholder="Masukan stok" value="{{ old('stock') }}">
                                    <p class="text-danger">{{ $errors->first('stock') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="rp_price">Harga Reseller (*)</label>
                                    <input type="number" class="form-control" name="reseller_price" id="rp_price" placeholder="Masukan harga reseller" value="{{ old('reseller_price') }}">
                                    <p class="text-danger">{{ $errors->first('reseller_price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="cp_price">Harga Customer (*)</label>
                                    <input type="number" class="form-control" name="customer_price" id="cp_price" placeholder="Masukan harga customer" value="{{ old('customer_price') }}">
                                    <p class="text-danger">{{ $errors->first('customer_price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="cat_price">Harga Katalog (*)</label>
                                    <input type="number" class="form-control" name="catalog_price" id="cat_price" placeholder="Masukan harga katalog" value="{{ old('catalog_price') }}">
                                    <p class="text-danger">{{ $errors->first('catalog_price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="discount">Diskon(Rp) (*)</label>
                                    <input type="number" class="form-control" name="discount" id="discount" placeholder="Masukan diskon" value="{{ old('discount') }}">
                                    <p class="text-danger">{{ $errors->first('discount') }}</p>
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    <input type="checkbox" id="rp" name="opsi_commision" value="0">--}}
{{--                                    <label for="rp">Rupiah</label>--}}
{{--                                    <p class="text-danger">{{ $errors->first('opsi_commision') }}</p>--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <label for="komisi_rp">Komisi(Rp) (*)</label>
                                    <input type="number" class="form-control js-commision_rp" name="commision_rp" id="komisi_rp" value="{{ old('commision_rp') }}" placeholder="Masukan Komisi Rp">
                                    <p class="text-danger">{{ $errors->first('commision_rp') }}</p>
{{--                                    <div class="form-group col-md-6 js-commision_persen d-none">--}}
{{--                                        <label for="komisi">Komisi (%)</label>--}}
{{--                                        <input type="text" class="form-control js-commision_persen" name="commision_persen" id="komisi" value="{{ old('commision_persen') }}" placeholder="Masukan Komisi %">--}}
{{--                                        <p class="text-danger">{{ $errors->first('commision_persen') }}</p>--}}
{{--                                    </div>--}}
                                </div>
                                <div class="form-group">
                                    <b class="text-danger">Contoh : 3 / 5</b> <br />
                                    <b class="text-danger">Artinya : 3 point / 5 produk</b>
                                    <div class="row">
                                    <div class="col-md-2">
                                        <label for="point">Point (*)</label>
                                        <input type="number" class="form-control" name="point" id="point" placeholder="Point" value="{{ old('point') }}">
                                        <p class="text-danger">{{ $errors->first('point') }}</p>
                                    </div>
                                        /
                                    <div class="col-md-2">
                                        <label for="pointPr">Produk (*)</label>
                                        <input type="number" class="form-control" name="point_product" id="pointPr" placeholder="Jumlah Produk" value="{{ old('point_product') }}">
                                        <p class="text-danger">{{ $errors->first('point_product') }}</p>
                                    </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description (*)</label>
                                    <textarea class="form-control js-summernote" id="description" name="description"></textarea>
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="category">Category (*)</label>
                                    <select class="form-control js-category" name="category" id="category">
                                        <option value="0">-- pilih kategori --</option>
                                        @foreach($listCategory as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('category') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="subcategory">Sub Category (*)</label>
                                    <select class="form-control js-subcategory" name="subcategory" id="subcategory">
                                    </select>
                                    <p class="text-danger">{{ $errors->first('subcategory') }}</p>
                                </div>
                            </div>
                            <div class="card-footer text-left">
                                <button class="btn btn-primary">Add</button>
                                <a href="{{ URL::previous() }}" class="btn btn-danger ml-2">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="{{ asset('public/admin/asset/js/product.js') }}"></script>
@endpush
