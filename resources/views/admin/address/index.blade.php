@extends('adminLayout.master')
@section('title')
    <title>Admin Address</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Admin Address</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="row mt-3 ml-2 mb-4">
                            <div class="col-md-{{ \Illuminate\Support\Facades\Auth::user()->isAdmin ? '4' : '8' }}">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal7">
                                    Tambah Alamat
                                </button>
                            </div>
                            @if(\Illuminate\Support\Facades\Auth::user()->isAdmin)
                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <a href="{{ route('admin.address') }}" class="btn btn-primary">Supplier</a>
                                        <a href="{{ route('admin.address.asadmin') }}" class="btn btn-primary">Admin</a>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4">
                                <form action="{{ route('admin.address') }}" method="GET" class="form-inline mr-auto">
                                    <div class="search-element">
                                        <input class="form-control" type="search" name="q" placeholder="Search" aria-label="Search" data-width="250">
                                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                        <div class="search-backdrop"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @if($message = session('message'))
                            <div class="row mt-0 ml-2">
                                <div class="col-md-6">
                                    <h4 class="btn btn-danger">{{ $message }}</h4>
                                </div>
                            </div>
                        @endif
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-md">
                                    <tr>
                                        <th>Nama Alamat</th>
                                        <th>No Hp</th>
                                        <th>Supplier</th>
                                        <th>Provinsi</th>
                                        <th>Kota</th>
                                        <th>Kecamatan</th>
                                        <th>Jalan</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($address as $adress)
                                        <tr>
                                            <td>{{ $adress->address_name }}</td>
                                            <td>{{ $adress->phone_number }}</td>
                                            <td>{{ $adress->user->username }}</td>
                                            <td>{{ $adress->province->name }}</td>
                                            <td>{{ $adress->city->name }}</td>
                                            <td>Kecamatan</td>
                                            <td>{{ $adress->street }}</td>
                                            <td><a href="{{ route('admin.address.update.status', $adress->id) }}" class="btn btn-primary" onclick="return confirm('Yakin mau ubah status')">{{ $adress->status }}</a></td>
                                            <td>
                                                <a href="{{ route('admin.address.delete', $adress->id) }}" class="btn btn-danger" onclick="return confirm('Yakin mau dihapus?')">Hapus</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @forelse($address as $adress)
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center mt-2"><b>No Address</b></td>
                                        </tr>
                                    @endforelse

                                </table>
                            </div>
                        </div>
                        <div class="paginate float-right mt-3 ml-3">
                            <p class="ml-3"><b>Jumlah Data : {{ $address->total() }}</b></p>
                            {{ $address->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<!-- Modal -->
<div class="modal fade" id="exampleModal7" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Alamat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.address.save') }}" name="form-product" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="address_name">Nama Alamat</label>
                        <input type="text" class="form-control" name="address_name" id="address_name" placeholder="Nama Alamat" value="{{ old('address_name') }}">
                        <p class="text-danger">{{ $errors->first('address_name') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Provinsi</label>
                        <select class="form-control provinsi-asal" name="province_origin">
                            <option value="0">-- pilih provinsi asal --</option>
                            @foreach ($provinces as $province => $value)
                                <option value="{{ $province  }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p class="text-danger">{{ $errors->first('province_origin') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Kota</label>
                        <select class="form-control kota-asal" name="city_origin">
                            <option value="">-- pilih kota asal --</option>
                        </select>
                        <p class="text-danger">{{ $errors->first('city_origin') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Kecamatan</label>
                        <select class="form-control kecamatan-asal" name="district_origin">
                            <option value="">-- pilih kecamatan asal --</option>
                        </select>
                        <p class="text-danger">{{ $errors->first('district_origin') }}</p>
                    </div>
                    <div class="form-group">
                        <label for="address_street">Nama Jalan</label>
                        <input type="text" class="form-control" name="street" id="address_street" placeholder="Alamat Lengkap" value="{{ old('street') }}">
                        <p class="text-danger">{{ $errors->first('street') }}</p>
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

@push('script')
    <script src="{{ asset('public/admin/asset/js/address.js') }}"></script>
@endpush



