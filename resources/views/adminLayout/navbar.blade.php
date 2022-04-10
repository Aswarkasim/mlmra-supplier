  <div id="app">
    <div class="main-wrapper">
 <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <div class="d-sm-none d-lg-inline-block">{{ \Illuminate\Support\Facades\Auth::user()->full_name }}</div></a>
            <div class="dropdown-menu dropdown-menu-right">
                  <form action="/logout" class="dropdown-item has-icon text-danger" method="POST">
                      @csrf
                      <button type="submit" class="fas fa-sign-out btn btn-primary mt-0">Logout</button>
                  </form>
              </a>
            </div>
          </li>
        </ul>
      </nav>
