@extends('layout.main')
@section('main', 'Data Barang Terhapus')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row mb-1">
          <div class="col">
            <h4 class="card-title ml-1">Data Barang Pendukung yang Dihapus (Trash)</h4>
          </div>
          <div class="col text-end d-flex align-items-end justify-content-end">
            <a href="{{ route('barang.index') }}" class="btn btn-primary mdi mdi-arrow-left-bold btn-icon-prepend">
              Kembali ke Data Barang Pendukung
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
                <th>Jumlah</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($barang as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->jumlah_barang }}</td>
                <td>{{ $item->deleted_at }}</td>
               <td class="d-flex gap-2">
                <form action="{{ route('barang.restore', $item->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <button type="submit" class="btn btn-success btn-sm mr-2">Restore</button>
                </form>
                <form action="{{ route('barang.forceDelete', $item->id) }}" method="POST">
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
                <td colspan="6" class="text-center">Tidak ada data yang terhapus.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>


@endsection

