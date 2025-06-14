@extends('layout.main')
@section('main', 'elektronik')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row mb-1">
          <div class="col">
            <h4 class="card-title ml-1">Aset Barang Elektronik Sekolah</h4>
          </div>
          <div class="col text-end d-flex align-items-end justify-content-end">
            <a href="{{ route('elektronik.create')}}" class="btn btn-success mdi mdi-upload btn-icon-prepend">
              Tambah Data
            </a>
          </div>
        </div>     
         <div class="row ml-1">
    <form action="{{ route('elektronik.index') }}" method="GET" class="d-flex">
      <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari barang..." value="{{ request('search') }}">
      <button type="submit" class="btn btn-primary btn-sm ms-2">Cari</button>
    </form>
  </div>      
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>
                  No
                </th>
                <th>
                  Kode Barang
                </th>
                <th>
                  Nama Barang
                </th>
                <th>
                  Merk
                </th>
                <th>
                  Type
                </th>
                <th>
                  Tanggal Peroleh Barang
                </th>
                <th>
                  Tanggal & Jam Input Barang
                </th>
                <th>
                  Asal Usul
                </th>
                <th>
                  Cara Perolehan
                </th>
                <th>
                  Jumlah Barang
                </th>
                <th>
                  Harga Per Unit Barang
                </th>
                <th>
                  Total Harga
                </th>
                @if(in_array(Auth::user()->role, ['A']))
                <th>
                  Aksi
                </th>
                @endif
              </tr>
            </thead>
           <tbody>
  @forelse ($elektronik as $item)
<tr>
  <td>{{ $loop->iteration }}</td>                
  <td>{{ $item['kode_barang'] }}</td>
  <td>{{ $item['nama_barang'] }}</td>
  <td>{{ $item['merk'] }}</td>
  <td>{{ $item['type'] }}</td>
  <td>{{ $item['tgl_peroleh'] }}</td>
  <td>{{ $item['created_at'] }}</td>
  <td>{{ $item['asal_usul'] }}</td>
  <td>{{ $item['cara_peroleh'] }}</td>
  <td>{{ $item['jumlah_brg'] }}</td>
  <td>Rp {{ number_format($item['harga_perunit'], 0, ',', '.') }}</td>
  <td>Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
   @if(in_array(Auth::user()->role, ['A']))
  <td>
    <div class="d-flex justify-content-center">
      <a href="{{ route('elektronik.edit', $item->id) }}">
        <button class="btn btn-success btn-sm mx-3">Edit</button>
      </a>
      <form method="POST" action="{{ route('elektronik.destroy', $item->id) }}">
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
  <td colspan="13" class="text-center">Tidak ada data Barang Mobiler.</td>
</tr>
@endforelse

@if(count($elektronik) > 0)
<tr>
  <td colspan="11" class="text-end fw-bold">Total Keseluruhan</td>
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
