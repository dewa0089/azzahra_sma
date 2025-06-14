@extends('layout.main')
@section('main', 'peminjaman')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <div class="row mb-3">
          <div class="col">
            <h4 class="card-title">Peminjaman Barang</h4>
            @if(request('nama_peminjam'))
              <p class="text-muted">Hasil filter untuk: <strong>{{ request('nama_peminjam') }}</strong></p>
            @endif
          </div>
          @if(in_array(Auth::user()->role, ['U']))
          <div class="col text-end d-flex align-items-end justify-content-end">
            <a href="{{ route('peminjaman.create') }}" class="btn btn-success mdi mdi-upload btn-icon-prepend">
              Ajukan Peminjaman
            </a>
          </div>
          @endif
        </div>

        {{-- Form Filter --}}
       {{-- Form Filter --}}
@if(in_array(Auth::user()->role, ['A']))
<form method="GET" action="{{ route('peminjaman.index') }}" class="mb-4">
  <div class="row">
    <div class="col-md-4">
      <select name="nama_peminjam" class="form-control">
        <option value="">-- Pilih Nama Peminjam --</option>
        @foreach($users as $user)
          <option value="{{ $user->name }}" {{ request('nama_peminjam') == $user->name ? 'selected' : '' }}>
            {{ $user->name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary">Filter</button>
    </div>
  </div>
</form>
@endif


        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah Peminjaman</th>
                <th>Tanggal Peminjaman</th>
                @if(in_array(Auth::user()->role, ['A']))
                <th>Tanggal & Jam Input Peminjaman</th>
                @endif
                <th>Tanggal Batas Pengembalian</th>
                <th>Status</th>
                @if(in_array(Auth::user()->role, ['U', 'A']))
                <th>Aksi</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @forelse ($peminjaman as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item['nama_peminjam'] }}</td>
                <td>{{ $item['barang']['kode_barang'] }}</td>
                <td>{{ $item['barang']['nama_barang'] }}</td>
                <td>{{ $item['jumlah_peminjam'] }}</td>
                <td>{{ $item['tgl_peminjam'] }}</td>
                @if(in_array(Auth::user()->role, ['A']))
                <td>{{ $item['created_at'] }}</td>
                @endif
                <td>{{ $item['tgl_kembali'] }}</td>
                <td>
                  <span class="badge 
                    @if($item->status == 'Disetujui') bg-success 
                    @elseif($item->status == 'Ditolak') bg-danger 
                    @elseif($item->status == 'Dibatalkan') bg-secondary 
                    @else bg-warning text-dark 
                    @endif">
                    {{ $item->status }}
                  </span>
                </td>
                @if(in_array(Auth::user()->role, ['U', 'A']))
                <td>
                  <div class="d-flex justify-content-start">
                    @if($item->status == 'Menunggu Persetujuan')
                      @if(in_array(Auth::user()->role, ['A']))
                      <form action="{{ route('peminjaman.setujui', $item->id) }}" method="POST" style="margin-right: 10px;">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-success">Setujui</button>
                      </form>
                      <form action="{{ route('peminjaman.tolak', $item->id) }}" method="POST" style="margin-right: 10px;">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-warning">Tolak</button>
                      </form>
                      @endif
                      @if(in_array(Auth::user()->role, ['U']))
                      <form action="{{ route('peminjaman.batalkan', $item->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-danger">Batalkan</button>
                      </form>
                      @endif
                    @else
                      <span class="text-muted">Tidak ada aksi</span>
                    @endif
                  </div>
                </td>
                @endif
              </tr>
              @empty
              <tr>
                <td colspan="10" class="text-center">Tidak ada data Peminjaman.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
  function confirmAction(form, message) {
    event.preventDefault();
    swal({
      title: "Apakah Anda yakin?",
      text: message,
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDo) => {
      if (willDo) {
        form.submit();
      }
    });
  }

  document.querySelectorAll('form button.btn-success').forEach(button => {
    button.addEventListener('click', function(event) {
      let form = this.closest('form');
      confirmAction(form, "Setujui peminjaman ini?");
    });
  });

  document.querySelectorAll('form button.btn-warning').forEach(button => {
    button.addEventListener('click', function(event) {
      let form = this.closest('form');
      confirmAction(form, "Tolak peminjaman ini?");
    });
  });

  document.querySelectorAll('form button.btn-danger').forEach(button => {
    button.addEventListener('click', function(event) {
      let form = this.closest('form');
      confirmAction(form, "Batalkan peminjaman ini?");
    });
  });
</script>

<script>
  @if (Session::get('success'))
    toastr.success("{{ Session::get('success') }}")
  @endif

  @if ($errors->has('stok'))
    toastr.error("{{ $errors->first('stok') }}")
  @endif
</script>
@endsection
