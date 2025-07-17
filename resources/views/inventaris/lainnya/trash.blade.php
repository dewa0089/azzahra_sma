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
                 <td class="d-flex gap-2">
                <form action="{{ route('lainnya.restore', $item->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <button type="submit" class="btn btn-success btn-sm mr-2">Restore</button>
                </form>
                <form action="{{ route('lainnya.forceDelete', $item->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="btn btn-danger btn-sm show_confirm"
                          data-toggle="tooltip"
                          title="Hapus Permanen"
                          data-nama="{{ $item->nama_barang }}">
                      Hapus Permanen
                  </button>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection


