<div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
              <a href="#">{{ \Illuminate\Support\Facades\Auth::user()->full_name }}</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
              @if(\Illuminate\Support\Facades\Auth::user()->isAdmin)
                  <a href="#">Admin</a>
              @else
                  <a href="#">Supplier</a>
              @endif
          </div>
          <ul class="sidebar-menu">
                  <li class="menu-header">Dashboard</li>
                  <li class="nav-item dropdown {{ request()->segment(2) == 'dashboard' ? 'active' : '' }}">
                      <a href="/admin/dashboard" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                  </li>
{{--              @if(\Illuminate\Support\Facades\Auth::user()->isAdmin)--}}
{{--              <li class="menu-header">Master</li>--}}
{{--              <li class="nav-item dropdown {{ request()->segment(2) == 'homepage' ? 'active' : '' }}">--}}
{{--                  <a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i> <span>Homepage</span></a>--}}
{{--                  <ul class="dropdown-menu">--}}
{{--                      <li><a class="nav-link" href="{{ route('admin.mainContent.edit') }}">Main Content</a></li>--}}
{{--                      <li><a class="nav-link" href="{{ route('admin.featured') }}">Featured</a></li>--}}
{{--                      <li><a class="nav-link" href="{{ route('admin.keuntungan') }}">Profit</a></li>--}}
{{--                  </ul>--}}
{{--              </li>--}}
{{--              @endif--}}
              <li class="nav-item dropdown {{ request()->segment(2) == 'product' ? 'active' : '' }}">
                <a href="{{ route('admin.product') }}" class="nav-link"><i class="fas fa-shopping-bag"></i> <span>Produk</span></a>
              </li>
              <li class="nav-item dropdown {{ request()->segment(2) == 'category' ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown"><i class="fas fa-address-card"></i> <span>Kategori</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('admin.category') }}">Kategori</a></li>
                      <li><a class="nav-link" href="{{ route('admin.subcategory') }}">Sub Kategori Produk</a></li>
                  </ul>
              </li>
{{--              <li class="nav-item dropdown {{ request()->segment(2) == 'brand' ? 'active' : '' }}">--}}
{{--                  <a href="{{ route('admin.brand') }}" class="nav-link"><i class="fas fa-shopping-bag"></i> <span>Brand</span></a>--}}
{{--              </li>--}}
              @if(\Illuminate\Support\Facades\Auth::user()->isAdmin)
              <li class="nav-item dropdown {{ request()->segment(2) == 'supplier' ? 'active' : '' }}">
                <a href="{{ route('admin.supplier') }}" class="nav-link"><i class="fas fa-pencil-ruler"></i> <span>Supplier</span></a>
              </li>
              <li class="nav-item dropdown {{ request()->segment(2) == 'reseller' ? 'active' : '' }}">
                  <a href="{{ route('admin.reseller') }}" class="nav-link"><i class="fas fa-th"></i> <span>Reseller</span></a>
              </li>
              <li class="nav-item dropdown {{ request()->segment(2) == 'customer' ? 'active' : '' }}">
                  <a href="{{ route('admin.customer') }}" class="nav-link"><i class="fas fa-pencil-ruler"></i> <span>Customer</span></a>
              </li>
              @endif
              <li class="nav-item dropdown {{ request()->segment(2) == 'transaction' ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i> <span>Transaksi</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('admin.resellerTransaction') }}">Transaksi Reseller</a></li>
                      <li><a class="nav-link" href="{{ route('admin.customerTransaction') }}">Transaksi Customer</a></li>
                  </ul>
              </li>
              <li class="nav-item dropdown {{ request()->segment(2) == 'payment' ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i> <span>Pembayaran</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('admin.customerPayment') }}">Pembayaran Customer</a></li>
                      <li><a class="nav-link" href="{{ route('admin.resellerPayment') }}">Pembayaran Reseller</a></li>
                  </ul>
              </li>
              <li class="nav-item dropdown {{ request()->segment(2) == 'commision' ? 'active' : '' }}">
                  <a href="{{ route('admin.commision') }}" class="nav-link"><i class="fas fa-th-large"></i> <span>Komisi</span></a>
              </li>
              @if(\Illuminate\Support\Facades\Auth::user()->isAdmin)
              <li class="nav-item dropdown {{ request()->segment(2) == 'testimoni' ? 'active' : '' }}">
                  <a href="{{ route('admin.testimoni') }}" class="nav-link"><i class="fas fa-th-large"></i> <span>Testimoni</span></a>
              </li>
              <li class="nav-item dropdown {{ request()->segment(2) == 'comment' ? 'active' : '' }}">
                  <a href="{{ route('admin.comment') }}" class="nav-link"><i class="fas fa-th-large"></i> <span>Komentar</span></a>
              </li>
              <li class="nav-item dropdown {{ request()->segment(2) == 'notification' ? 'active' : '' }}">
                  <a href="{{ route('admin.notification') }}" class="nav-link"><i class="fas fa-th-large"></i> <span>Notifikasi</span></a>
              </li>
              <li class="nav-item dropdown {{ request()->segment(2) == 'coupon' ? 'active' : '' }}">
                  <a href="{{ route('admin.coupon') }}" class="nav-link"><i class="fas fa-th-large"></i> <span>Kupon</span></a>
              </li>
              @endif
              <li class="nav-item dropdown {{ request()->segment(2) == 'profile' ? 'active' : '' }}">
                  <a href="{{ route('admin.profile.edit') }}" class="nav-link"><i class="fas fa-th-large"></i> <span>Akun</span></a>
              </li>
              <li class="nav-item dropdown {{ request()->segment(2) == 'address' ? 'active' : '' }}">
                  <a href="{{ route('admin.address') }}" class="nav-link"><i class="fas fa-th-large"></i> <span>Alamat</span></a>
              </li>
              <li class="nav-item dropdown {{ request()->segment(2) == 'bank' ? 'active' : '' }}">
                  <a href="{{ route('admin.bankAccount') }}" class="nav-link"><i class="fas fa-th-large"></i> <span>Rekening</span></a>
              </li>
        </aside>
      </div>
