@extends('layout.main')
@section('main', 'lainnya')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row mb-1">
          <div class="col">
            <h4 class="card-title ml-1">Data Barang Lainnya yang Dihapus (Trash)</h4>
          </div>
          <div class="col text-end d-flex align-items-end justify-content-end">
            <a href="{{ route('lainnya.index') }}" class="btn btn-primary mdi mdi-arrow-left-bold btn-icon-prepend">
              Kembali ke Data lainnya
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
                <th>Asal Usul</th>
                <th>Cara Peroleh</th>
                <th>Jumlah</th>
                <th>Harga/Unit</th>
                <th>Total Harga</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($lainnya as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->merk }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->tgl_peroleh }}</td>
                <td>{{ $item->asal_usul }}</td>
                <td>{{ $item->cara_peroleh }}</td>
                <td>{{ $item->jumlah_brg }}</td>
                <td>Rp {{ number_format($item->harga_perunit, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                <td>{{ $item->deleted_at }}</td>
                <td>
                  <form action="{{ route('lainnya.restore', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Restore</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="13" class="text-center">Tidak ada data yang terhapus.</td>
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
<script>
    @if (Session::get('success'))
        toastr.success("{{ Session::get('success') }}")
    @endif
    @if (Session::get('error'))
        toastr.error("{{ Session::get('error') }}")
    @endif
</script>
@endsection
