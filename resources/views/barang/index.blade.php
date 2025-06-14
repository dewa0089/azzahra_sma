@extends('layout.main')
@section('main', 'barang')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row mb-1">
          <div class="col">
            <h4 class="card-title">Aset Barang Sekolah</h4>
          </div>
          @if(in_array(Auth::user()->role, ['A']))
          <div class="col text-end d-flex align-items-end justify-content-end">
            <a href="{{ route('barang.create')}}" class="btn btn-success mdi mdi-upload btn-icon-prepend">
              Tambah Data
            </a>
          </div>
          @endif
        </div> 
         <div class="row ml-1">
    <form action="{{ route('barang.index') }}" method="GET" class="d-flex">
      <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari barang..." value="{{ request('search') }}">
      <button type="submit" class="btn btn-primary btn-sm ms-2">Cari</button>
    </form>
  </div>                
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah Barang</th>
                <th>Jumlah Barang Rusak</th>
                <th>Jumlah Barang Hilang</th>
                <th>Tanggal Peroleh</th>
                <th>Tanggal & Jam Input Barang</th>
                <th>Harga Per Unit</th>
                <th>Total Harga</th>
                @if(in_array(Auth::user()->role, ['A']))
                <th>Aksi</th>
                @endif
              </tr>
            </thead>
            <tbody>
@forelse ($barang as $item)
<tr>
  <td>{{ $loop->iteration }}</td>                
  <td>{{ $item['kode_barang'] }}</td>
  <td>{{ $item['nama_barang'] }}</td>
  <td>{{ $item['jumlah_barang'] }}</td>
  <td>{{ $item['jumlah_rusak'] }}</td>
  <td>{{ $item['jumlah_hilang'] }}</td>
  <td>{{ $item['tgl_peroleh'] }}</td>
  <td>{{ $item['created_at'] }}</td>
  <td>Rp {{ number_format($item['harga_perunit'], 0, ',', '.') }}</td>
  <td>Rp {{ number_format($item['jumlah_barang'] * $item['harga_perunit'], 0, ',', '.') }}</td>
  @if(in_array(Auth::user()->role, ['A']))
  <td>
    <div class="d-flex justify-content-center">
      <a href="{{ route('barang.edit', $item->id) }}">
        <button class="btn btn-success btn-sm mx-3">Edit</button>
      </a>
      <form method="POST" action="{{ route('barang.destroy', $item->id) }}">
        @method('delete')
        @csrf
        <button type="submit" class="btn btn-danger btn-sm show_confirm"
                data-toggle="tooltip" title='Delete'
                data-nama='{{ $item->nama_barang }}'>Hapus Data</button>
      </form>
    </div>
  </td>
  @endif
</tr>
@empty
<tr>
  <td colspan="11" class="text-center">Tidak ada data Barang.</td>
</tr>
@endforelse
@if(count($barang) > 0)
<tr>
  <td colspan="9" class="text-end fw-bold">Total Keseluruhan</td>
  <td class="fw-bold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
  <td></td>
</tr>
@endif
</tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  @if (Session::get('success'))
    toastr.success("{{ Session::get('success') }}")
  @endif
  @if (Session::get('error'))
            toastr.error("{{ Session::get('error') }}")
        @endif
</script>
@endsection
