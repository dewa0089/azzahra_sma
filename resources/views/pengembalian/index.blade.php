@extends('layout.main')
@section('main', 'pengembalian')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <div class="row mb-3">
          <div class="col">
            <h4 class="card-title">Pengembalian Barang</h4>
          </div>
        </div>

        {{-- Form Filter Nama Peminjam --}}
        @if(isset($users) && in_array(Auth::user()->role, ['A', 'K', 'W']))
        <form method="GET" action="{{ route('pengembalian.index') }}" class="mb-4">
          <div class="row">
            <div class="col-md-4">
              <select name="nama_peminjam" class="form-control">
                <option value="">-- Pilih Nama Peminjam --</option>
                @foreach($users as $u)
                <option value="{{ $u->name }}" {{ request('nama_peminjam') == $u->name ? 'selected' : '' }}>
                  {{ $u->name }}
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
                <th>Tanggal Batas Pengembalian</th>
                <th>Jumlah Barang Baik</th>
                <th>Jumlah Barang Rusak</th>
                <th>Jumlah Barang Hilang</th>
                <th>Tanggal Pengembalian</th>
                @if(in_array(Auth::user()->role, ['A']))
                <th>Tanggal & Jam Input</th>
                @endif
                <th>Keterangan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($pengembalian as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->peminjaman->nama_peminjam }}</td>
                <td>{{ $item->peminjaman->barang->kode_barang }}</td>
                <td>{{ $item->peminjaman->barang->nama_barang }}</td>
                <td>{{ $item->peminjaman->jumlah_peminjam }}</td>
                <td>{{ $item->peminjaman->tgl_kembali }}</td>
                <td>{{ $item->jumlah_brg_baik ?? '-' }}</td>
                <td>{{ $item->jumlah_brg_rusak ?? '-' }}</td>
                <td>{{ $item->jumlah_brg_hilang ?? '-' }}</td>
                <td>{{ $item->tgl_pengembalian ?? '-' }}</td>
                @if(in_array(Auth::user()->role, ['A']))
                <td>{{ $item->created_at }}</td>
                @endif
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td>
                  <span class="badge 
                    @if($item->status == 'Disetujui') bg-success 
                    @elseif($item->status == 'Menunggu Persetujuan') bg-warning text-dark 
                    @else bg-secondary 
                    @endif">
                    {{ $item->status }}
                  </span>
                </td>
                <td>
                  <div class="d-flex justify-content-center">
                    @if ($item->status == 'Belum Dikembalikan') 
                      <a href="{{ route('pengembalian.edit', $item->id) }}">
                        @if(in_array(Auth::user()->role, ['U']))
                        <button class="btn btn-success btn-sm mx-2">Ajukan Pengembalian</button>
                        @endif
                      </a>
                    @elseif ($item->status == 'Menunggu Persetujuan')
                      @if(in_array(Auth::user()->role, ['A']))
                      <form id="form-setujui-{{ $item->id }}" action="{{ route('pengembalian.setujui', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="button" class="btn btn-primary btn-sm btn-setujui" data-id="{{ $item->id }}">
                          Setujui
                        </button>
                      </form>
                      @endif
                    @else
                      <span class="text-muted">Tidak ada aksi</span>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="14" class="text-center">Tidak ada data Pengembalian.</td>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert"></script>
<script>
  @if (Session::get('success'))
    toastr.success("{{ Session::get('success') }}")
  @endif

  // SweetAlert untuk tombol Setujui
  document.querySelectorAll('.btn-setujui').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      swal({
        title: "Apakah Anda yakin?",
        text: "Anda akan menyetujui pengembalian ini.",
        icon: "warning",
        buttons: ["Batal", "Setujui"],
        dangerMode: true,
      }).then((willApprove) => {
        if (willApprove) {
          document.getElementById('form-setujui-' + id).submit();
        }
      });
    });
  });
</script>
@endsection
