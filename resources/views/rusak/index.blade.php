@extends('layout.main')
@section('main', 'barang_rusak')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      <div class="row mb-1">
          <div class="col">
            <h4 class="card-title">Inventaris Barang Rusak</h4>
          </div>
          <div class="col text-end d-flex align-items-end justify-content-end">
            <a href="{{ route('rusak.create') }}" class="btn btn-success mdi mdi-upload btn-icon-prepend">
              Tambah Barang Rusak
            </a>
          </div>
        </div>                
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Type</th>
                <th>Tanggal Peroleh</th>
                <th>Tanggal & Jam Input Barang Rusak</th>
                <th>Asal Usul</th>
                <th>Cara Peroleh</th>
                <th>Jumlah Rusak</th>
                <th>Gambar</th>
                <th>Tgl Rusak</th>
                <th>Keterangan</th>
                <th>Harga/Unit</th>
                <th>Status</th>
                @if(in_array(Auth::user()->role, ['A']))
                <th>Aksi</th>
                @endif
              </tr>
            </thead>
            <tbody>
@forelse($rusak as $item)
@php
    $barang = $item->elektronik ?? $item->mobiler ?? $item->lainnya;
@endphp
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $barang->kode_barang ?? '-' }}</td>
    <td>{{ $barang->nama_barang ?? '-' }}</td>
    <td>{{ $barang->merk ?? '-' }}</td>
    <td>{{ $barang->type ?? '-' }}</td>
    <td>{{ $barang->tgl_peroleh ?? '-' }}</td>
    <td>{{ $item['created_at'] }}</td>
    <td>{{ $barang->asal_usul ?? '-' }}</td>
    <td>{{ $barang->cara_peroleh ?? '-' }}</td>
    <td>{{ $item->jumlah_brg_rusak }}</td>
    <td>
       @if(!empty($item->gambar_brg_rusak) && file_exists(public_path('gambar/' . $item->gambar_brg_rusak)))
    <img src="{{ asset('gambar/' . $item->gambar_brg_rusak) }}" alt="Gambar Rusak" width="80">
@else
    Tidak ada gambar
@endif
    </td>
    <td>{{ $item->tgl_rusak }}</td>
    <td>{{ $item->keterangan ?? '-' }}</td>
    <td>Rp {{ number_format($barang->harga_perunit ?? 0, 0, ',', '.') }}</td>
 <td>
  <span class="badge 
    @if($item->status == 'Berhasil Dimusnakan') bg-danger 
    @else bg-warning text-dark 
    @endif">
    {{ $item->status }}
  </span>
</td>

@if(in_array(Auth::user()->role, ['A']))
<td>
  <div class="d-flex justify-content-center">
     @if ($item->status == 'Rusak')
    <a href="{{ route('pemusnaan.create', ['rusak_id' => $item->id]) }}" class="btn btn-warning btn-sm mx-1">
        Lakukan Pemusnaan
    </a>

    <a href="{{ route('perbaikan.mulai', ['rusak_id' => $item->id]) }}" 
   class="btn btn-info btn-sm mx-1 btn-perbaikan">
    Lakukan Perbaikan
</a>

    <!-- Tombol Hapus -->
    <form method="POST" action="{{ route('rusak.destroy', $item->id) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm show_confirm mx-1">Hapus Data</button>
    </form>
@endif

@if ($item->status == 'Dalam Perbaikan')
    <a href="{{ route('perbaikan.selesaikan', ['rusak_id' => $item->id]) }}" 
   class="btn btn-success btn-sm mx-1 btn-selesai-perbaikan">
    Selesai
</a>
@endif



  </div>
</td>


@endif

 @empty
  <tr>
    <td colspan="16" class="text-center">Tidak ada data Barang Rusak.</td>
  </tr>

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
   // Konfirmasi untuk tombol "Lakukan Perbaikan"
  document.querySelectorAll('.btn-perbaikan').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const href = this.getAttribute('href');
      swal({
        title: "Mulai Perbaikan?",
        text: "Status barang akan berubah menjadi 'Dalam Perbaikan'.",
        icon: "info",
        buttons: ["Batal", "Ya, Lanjutkan!"],
        dangerMode: false,
      }).then((willGo) => {
        if (willGo) {
          window.location.href = href;
        }
      });
    });
  });

  // Konfirmasi untuk tombol "Selesai Perbaikan"
  document.querySelectorAll('.btn-selesai-perbaikan').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const href = this.getAttribute('href');
      swal({
        title: "Selesaikan Perbaikan?",
        text: "Barang akan dinyatakan selesai diperbaiki.",
        icon: "success",
        buttons: ["Batal", "Ya, Selesaikan!"],
        dangerMode: false,
      }).then((willGo) => {
        if (willGo) {
          window.location.href = href;
        }
      });
    });
  });
  @if (Session::get('success'))
    toastr.success("{{ Session::get('success') }}");
  @endif
</script>
@endsection

