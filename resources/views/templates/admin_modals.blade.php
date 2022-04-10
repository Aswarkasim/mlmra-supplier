<!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Varian Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.varianProduct.save', $product->id) }}" name="form-product" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="weight">Berat (Gram)</label>
                                        <input type="number" class="form-control" name="weight" value="{{ old('weight') }}" id="weight" placeholder="Masukan berat">
                                        <p class="text-danger">{{ $errors->first('weight') }}</p>
                                    </div>
{{--                                    <div class="col">--}}
{{--                                        <label for="weight-stok">Stok Berat</label>--}}
{{--                                        <input type="number" class="form-control" name="weight_stock" value="{{ old('weight_stock') }}" id="weight-stok" placeholder="Masukan stok berat">--}}
{{--                                        <p class="text-danger">{{ $errors->first('weight_stock') }}</p>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="color">Warna</label>
                                        <input type="text" class="form-control" name="color" value="{{ old('color') }}" id="color" placeholder="Masukan warna">
                                        <p class="text-danger">{{ $errors->first('color') }}</p>
                                    </div>
                                    <div class="col">
                                        <label for="color-stok">Stok Warna</label>
                                        <input type="number" class="form-control" name="color_stock" value="{{ old('color_stock') }}" id="color-stok" placeholder="Masukan stok warna">
                                        <p class="text-danger">{{ $errors->first('color_stock') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="size">Ukuran (S/M/L/XL)</label>
                                        <input type="text" class="form-control" name="size" value="{{ old('size') }}" id="size" placeholder="Masukan ukuran">
                                        <p class="text-danger">{{ $errors->first('size') }}</p>
                                    </div>
                                    <div class="col">
                                        <label for="size-stok">Stok Ukuran</label>
                                        <input type="number" class="form-control" name="size_stock" value="{{ old('size_stock') }}" id="size-stok" placeholder="Masukan stok ukuran">
                                        <p class="text-danger">{{ $errors->first('size_stock') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="type">Tipe</label>
                                        <input type="text" class="form-control" name="type" value="{{ old('type') }}" id="type" placeholder="Masukan tipe">
                                        <p class="text-danger">{{ $errors->first('type') }}</p>
                                    </div>
                                    <div class="col">
                                        <label for="type-stok">Stok Tipe</label>
                                        <input type="number" class="form-control" name="type_stock" value="{{ old('type_stock') }}" id="type-stok" placeholder="Masukan stok tipe">
                                        <p class="text-danger">{{ $errors->first('type_stock') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="taste">Rasa</label>
                                        <input type="text" class="form-control" name="taste" value="{{ old('taste') }}" id="taste" placeholder="Masukan rasa">
                                        <p class="text-danger">{{ $errors->first('taste') }}</p>
                                    </div>
                                    <div class="col">
                                        <label for="taste-stok">Stok Rasa</label>
                                        <input type="number" class="form-control" name="taste_stock" value="{{ old('taste_stock') }}" id="taste-stok" placeholder="Masukan stok rasa">
                                        <p class="text-danger">{{ $errors->first('taste_stock') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


