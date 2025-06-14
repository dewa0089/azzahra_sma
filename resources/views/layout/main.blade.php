<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('main')</title>
  <!-- base:css -->
  <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('images/logo.jpeg') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>
  <div class="container-scroller d-flex">
    <!-- partial:./partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
        <li class="nav-item sidebar-category">
          <p>SMA Az-Zahra</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('dashboard') }}">
            <i class="mdi mdi-view-quilt menu-icon"></i>
            <span class="menu-title">Dashboard</span>
          </a>
        </li>
        @if(in_array(Auth::user()->role, ['A', 'K', 'W']))
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
            <i class="mdi mdi-palette menu-icon"></i>
            <span class="menu-title">Barang Induk</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{ url('mobiler') }}">Barang Mobiler</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{ url('elektronik') }}">Barang Elektronik</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{ url('lainnya') }}">Barang Lainnya</a></li>
            </ul>
          </div>
        </li>
        @endif
        @if(in_array(Auth::user()->role, ['A', 'U']))
        <li class="nav-item">
          <a class="nav-link" href="{{ url('barang') }}">
            <i class="mdi mdi-cube menu-icon"></i>
            <span class="menu-title">Barang Pendukung</span>
          </a>
        </li>
        @endif
        @if(in_array(Auth::user()->role, ['A', 'U']))
        <li class="nav-item">
          <a class="nav-link" href="{{ url('peminjaman') }}">
            <i class="mdi mdi-file-restore menu-icon"></i>
            <span class="menu-title">Peminjaman Barang</span>
          </a>
        </li>
        @endif
        @if(in_array(Auth::user()->role, ['A', 'U']))
        <li class="nav-item">
          <a class="nav-link" href="{{ url('pengembalian') }}">
            <i class="mdi mdi-grid-large menu-icon"></i>
            <span class="menu-title">Pengembalian Barang</span>
          </a>
        </li>
        @endif
        @if(in_array(Auth::user()->role, ['A']))
        <li class="nav-item">
          <a class="nav-link" href="{{ url('rusak') }}">
            <i class="mdi mdi-folder-remove menu-icon"></i>
            <span class="menu-title">Barang Rusak</span>
          </a>
        </li>
        @endif

        @if(in_array(Auth::user()->role, ['A']))
        <li class="nav-item">
          <a class="nav-link" href="{{ url('perbaikan') }}">
            <i class="mdi mdi-glassdoor menu-icon"></i>
            <span class="menu-title">Perbaikan Barang</span>
          </a>
        </li>
        @endif

        @if(in_array(Auth::user()->role, ['A']))
        <li class="nav-item">
          <a class="nav-link" href="{{ url('pemusnaan') }}">
            <i class="mdi mdi-image-broken-variant menu-icon"></i>
            <span class="menu-title">Pemusnahan Barang</span>
          </a>
        </li>
        @endif
        
        <li class="nav-item">
          <a class="nav-link" href="{{ url('history') }}">
            <i class="mdi mdi-history menu-icon"></i>
            <span class="menu-title">Riwayat</span>
          </a>
        </li>

        @if(Auth::user()->role == 'A')
        <li class="nav-item">
          <a class="nav-link" href="{{ url('user') }}">
            <i class="mdi mdi-account menu-icon"></i>
            <span class="menu-title">Manajemen User</span>
          </a>
        </li>
        @endif
        @if(in_array(Auth::user()->role, ['A', 'K', 'W']))
        <li class="nav-item">
          <a class="nav-link" href="{{ url('laporan') }}">
            <i class="mdi mdi-file-chart menu-icon"></i>
            <span class="menu-title">Laporan Barang</span>
          </a>
        </li>
        @endif
      </ul>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:./partials/_navbar.html -->
      <nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <div class="ml-4 mb-2"><h4 class="font-weight-bold mb-0 d-none d-md-block mt-1">Selamat Datang, {{ Auth::user()->name }}</h4></div>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item mr-3">
              <h4 id="tanggal-waktu" class="mb-0 font-weight-bold d-none d-xl-block"></h4>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
        <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown" style="color: white">
                
                <span class="nav-profile-name font-weight-bold" style="color: white; font-size: 18px">{{ Auth::user()->name }}</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
          
                <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST"
            class="d-none">
            @csrf
        </form>
              </div>
            </li>
            <li class="nav-item">
              <a href="{{ url('history') }}" class="nav-link icon-link">
                <i class="mdi mdi-clock-outline" style="color: white"></i>
              </a>
            </li>
          </ul>
        </div>
      </nav>
      
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')

        </div>
        <!-- content-wrapper ends -->
        <!-- partial:./partials/_footer.html -->
        <footer class="footer">
          <div class="card">
            <div class="card-body">
              <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © azzahra.com 2025</span>
                <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Distributed By: <a href="https://azzahrasriwijaya.sch.id/sma-islam-az-zahrah/" target="_blank">Az-Zahra</a></span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Sekolah Menengah Atas Islam Az Zahra</span>
              </div>
            </div>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  
    <!-- base:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <script src="vendors/chart.js/Chart.min.js"></script>
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <!-- End plugin js for this page -->

    <!-- End custom js for this page-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @yield('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var nama = $(this).data("nama");
            event.preventDefault();
            swal({
                    title: `Apakah Anda yakin ingin menghapus data ${nama} ini?`,
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>
</body>

</html>