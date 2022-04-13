@extends('adminLayout.master')
@section('title')
    <title>Edit Product</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Product</h1>
        </div>
        <div class="section-body">
            @if(empty($varian))
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.product.update', $product->id) }}" name="form-product" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="action_type"/>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $product->title) }}" id="title" placeholder="Masukan judul" required="">
                                    <p class="text-danger">{{ $errors->first('title') }}</p>
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    <label>Brand Name</label>--}}
{{--                                    <select class="form-control js-category" name="brand" required>--}}
{{--                                        <option value="0">-- pilih brand --</option>--}}
{{--                                        @foreach($listBrand as $brand)--}}
{{--                                            <option value="{{ $brand->id }}" {{ $brand->id == $product->brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                    <p class="text-danger">{{ $errors->first('brand') }}</p>--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <label for="title">Stok</label>
                                    <input type="number" class="form-control" name="stock" value="{{ old('stock', $product->stock) }}" id="title" placeholder="Masukan stok" required="">
                                    <p class="text-danger">{{ $errors->first('stock') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="res_price">Harga Reseller</label>
                                    <input type="number" class="form-control" name="reseller_price" value="{{ old('reseller_price', $product->reseller_price) }}" id="res_price" placeholder="Masukan harga reseller" required="">
                                    <p class="text-danger">{{ $errors->first('reseller_price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="cus_price">Harga Customer</label>
                                    <input type="number" class="form-control" name="customer_price" value="{{ old('customer_price', $product->customer_price) }}" id="cus_price" placeholder="Masukan harga customer" required="">
                                    <p class="text-danger">{{ $errors->first('customer_price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="cat_price">Harga Kataclog</label>
                                    <input type="number" class="form-control" name="catalog_price" value="{{ old('catalog_price', $product->catalog_price) }}" id="cat_price" placeholder="Masukan harga katalog" required="">
                                    <p class="text-danger">{{ $errors->first('catalog_price') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="title">Diskon (Rp)</label>
                                    <input type="number" class="form-control" name="discount" value="{{ old('discount', $product->discount) }}" id="title" placeholder="Masukan diskon" required="">
                                    <p class="text-danger">{{ $errors->first('discount') }}</p>
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    <input type="checkbox" id="rp" name="opsi_commision" value="1" {{ $product->isCommisionRupiah ? 'checked' : '' }}>--}}
{{--                                    <label for="rp">Rupiah</label>--}}
{{--                                    <p class="text-danger">{{ $errors->first('opsi_commision') }}</p>--}}
{{--                                </div>--}}
                                <div class="row">
                                    <div class="form-group col-md-6 js-commision_rp {{ $product->isCommisionRupiah ? '-' : 'd-none' }}">
                                        <label for="komisi_rp">Komisi (Rp)</label>
                                        <input type="number" class="form-control js-commision_rp" value="{{ old('commision_rp', $product->commision_rp) }}" name="commision_rp" id="komisi_rp" placeholder="Masukan Komisi Rp">
                                        <p class="text-danger">{{ $errors->first('commision_rp') }}</p>
                                    </div>
                                    <div class="form-group col-md-6 js-commision_persen {{ $product->isCommisionRupiah ? 'd-none' : '' }}">
                                        <label for="komisi">Komisi (%)</label>
                                        <input type="number" class="form-control js-commision_persen" value="{{ old('commision_persen', $product->commision_persen) }}" name="commision_persen" id="komisi" placeholder="Masukan Komisi %">
                                        <p class="text-danger">{{ $errors->first('commision_persen') }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <b class="text-danger">Contoh : 3 / 5</b> <br />
                                    <b class="text-danger">Artinya : 3 point / 5 produk</b>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="point">Point (*)</label>
                                            <input type="number" class="form-control" name="point" id="point" placeholder="Point" value="{{ old('point', $product->point) }}">
                                            <p class="text-danger">{{ $errors->first('point') }}</p>
                                        </div>
                                        /
                                        <div class="col-md-2">
                                            <label for="pointPr">Produk (*)</label>
                                            <input type="number" class="form-control" name="point_product" id="pointPr" placeholder="Jumlah Produk" value="{{ old('point_product', $product->point_product) }}">
                                            <p class="text-danger">{{ $errors->first('point_product') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control js-summernote" id="description" name="description">{{ $product->description }}</textarea>
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control js-category" name="category" required>
                                        <option value="0">-- pilih kategori --</option>
                                        @foreach($listCategory as $category)
                                            <option value="{{ $category->id }}" {{ $category->id == $product->category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('category') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="subcategory">Sub Category (*)</label>
                                    <select class="form-control js-subcategory" name="subcategory" id="subcategory">
                                            @foreach($subCategory as $sub_category)
                                                <option value="{{ $sub_category->id }}" {{ $sub_category->id == $product->sub_category_id ? 'selected' : '' }}>{{ $sub_category->name }}</option>
                                            @endforeach
                                    </select>
                                    <p class="text-danger">{{ $errors->first('subcategory') }}</p>
                                </div>
                                <p><em><b>Note : Wajib diisi untuk varian produk</b></em></p>
                                <div class="row">
                                    <div class="col-md-2">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                            Tambah Varian
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0 mt-4">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-md">
                                            <tr>
                                                <th>Berat</th>
                                                <th>Warna</th>
                                                <th>Stok Warna</th>
                                                <th>Ukuran</th>
                                                <th>Stok Ukuran</th>
                                                <th>Tipe</th>
                                                <th>Stok Tipe</th>
                                                <th>Rasa</th>
                                                <th>Stok Rasa</th>
                                                <th>Aksi</th>
                                            </tr>
                                            @foreach($varianProduct as $varian)
                                                <tr>
                                                    <td>{{ $varian->weight ?: '-' }}</td>
                                                    <td>{{ $varian->color ?: '-' }}</td>
                                                    <td>{{ $varian->color_total ?: '-'  }}</td>
                                                    <td>{{ $varian->size ?: '-' }}</td>
                                                    <td>{{ $varian->size_total ?: '-' }}</td>
                                                    <td>{{ $varian->type ?: '-' }}</td>
                                                    <td>{{ $varian->type_total ?: '-' }}</td>
                                                    <td>{{ $varian->taste ?: '-' }}</td>
                                                    <td>{{ $varian->taste_total ?: '-' }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.varianProduct.edit', ['product_id' => $product->id, 'id' => $varian->id]) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                        <a href="{{ route('admin.varianProduct.delete', $varian->id) }}" class="btn btn-danger" onclick="return confirm('Yakin mau dihapus?')"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @forelse($varianProduct as $varian)
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center mt-2"><b>No Varian Product</b></td>
                                                </tr>
                                            @endforelse
                                        </table>
                                    </div>
                                </div>

                                <p><em><b>Note : Gambar max 5 mb </br>
                                            Note : Video max 10 mb</b></em></p>
                                <button type="button" class="btn btn-primary text-right mb-2" data-toggle="modal" data-target="#exampleModal3">
                                    Tambah Gambar
                                </button>
                                <button type="button" class="btn btn-primary text-right mb-2" data-toggle="modal" data-target="#exampleModal4">
                                    Tambah Video
                                </button>
                                <div class="row">
{{--                                    <video width="320" height="240" controls>--}}
{{--                                        <source src="{{ $video->file_name ? asset('storage/'.$video->file_name) : '' }}" type="video/mp4" autoplay>--}}
{{--                                    </video>--}}
                                    @foreach($media as $productImage)
                                        <div class="col-md-2 mr-4 mb-2">
                                            <a href="{{ route('admin.product.delete.image', ['id' => $productImage->id, 'product' => $product->id]) }}" onclick="return confirm('Yakin mau dihapus?')">
                                                <img src="{{ $productImage->file_name ? asset('/uploads/images/'.$productImage->file_name) : '' }}" height="120" width="180">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="box-footer mb-3 ml-4 mt-4">
                                <a href="{{ route('admin.product') }}" class="btn btn-info margin-r-5">CANCEL</a>
                                @if($product->status == \App\Enums\StatusType::DRAFT() || $product->status == \App\Enums\StatusType::ARCHIVED())
                                    <button type="button" class="btn btn-primary margin-r-5 js-product-action js-action-btn" data-action-type="SAVE">SAVE</button>
                                @endif
                                @if($product->status == \App\Enums\StatusType::DRAFT() || $product->status == \App\Enums\StatusType::PUBLISHED())
                                    <button type="button" class="btn btn-primary margin-r-5 js-product-action js-action-btn" data-action-type="PUBLISH">PUBLISH</button>
                                @endif
                                @if($product->status != \App\Enums\StatusType::ARCHIVED())
                                    <button type="button" class="btn btn-warning margin-r-5 js-product-action js-action-btn" data-action-type="ARCHIVE">ARCHIVE</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('admin.varianProduct.update', ['product_id' => $productId, 'id' => $varian->id]) }}" name="form-product" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label for="weight">Berat (Gram)</label>
                                            <input type="number" class="form-control" name="weight" value="{{ old('weight', $varian->weight) }}" id="weight" placeholder="Masukan berat">
                                            <p class="text-danger">{{ $errors->first('weight') }}</p>
                                        </div>
{{--                                        <div class="col">--}}
{{--                                            <label for="weight-stok">Stok Berat</label>--}}
{{--                                            <input type="number" class="form-control" name="weight_stock" value="{{ old('weight_stock', $varian->weight_total) }}" id="weight-stok" placeholder="Masukan stok berat">--}}
{{--                                            <p class="text-danger">{{ $errors->first('weight_stock') }}</p>--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label for="color">Warna</label>
                                            <input type="text" class="form-control" name="color" value="{{ old('color', $varian->color) }}" id="color" placeholder="Masukan warna">
                                            <p class="text-danger">{{ $errors->first('color') }}</p>
                                        </div>
                                        <div class="col">
                                            <label for="color-stok">Stok Warna</label>
                                            <input type="number" class="form-control" name="color_stock" value="{{ old('color_stock', $varian->color_total) }}" id="color-stok" placeholder="Masukan stok warna">
                                            <p class="text-danger">{{ $errors->first('color_stock') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label for="size">Ukuran (S/M/L/XL)</label>
                                            <input type="text" class="form-control" name="size" value="{{ old('size', $varian->size) }}" id="size" placeholder="Masukan ukuran">
                                            <p class="text-danger">{{ $errors->first('size') }}</p>
                                        </div>
                                        <div class="col">
                                            <label for="size-stok">Stok Ukuran</label>
                                            <input type="number" class="form-control" name="size_stock" value="{{ old('size_stock', $varian->size_total) }}" id="size-stok" placeholder="Masukan stok ukuran">
                                            <p class="text-danger">{{ $errors->first('size_stock') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label for="type">Tipe</label>
                                            <input type="text" class="form-control" name="type" value="{{ old('type', $varian->type) }}" id="type" placeholder="Masukan tipe">
                                            <p class="text-danger">{{ $errors->first('type') }}</p>
                                        </div>
                                        <div class="col">
                                            <label for="type-stok">Stok Tipe</label>
                                            <input type="number" class="form-control" name="type_stock" value="{{ old('type_stock', $varian->type_total) }}" id="type-stok" placeholder="Masukan stok tipe">
                                            <p class="text-danger">{{ $errors->first('type_stock') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label for="taste">Rasa</label>
                                            <input type="text" class="form-control" name="taste" value="{{ old('taste', $varian->taste) }}" id="taste" placeholder="Masukan rasa">
                                            <p class="text-danger">{{ $errors->first('taste') }}</p>
                                        </div>
                                        <div class="col">
                                            <label for="taste-stok">Stok Rasa</label>
                                            <input type="number" class="form-control" name="taste_stock" value="{{ old('taste_stock', $varian->taste_total) }}" id="taste-stok" placeholder="Masukan stok rasa">
                                            <p class="text-danger">{{ $errors->first('taste_stock') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ URL::previous() }}" class="btn btn-secondary">Back</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
@endsection

@include('templates.admin_modals');
@include('templates.admin_modals_upload_image_products');

@push('script')
    <script src="{{ asset('public/admin/asset/js/product.js') }}"></script>
@endpush
