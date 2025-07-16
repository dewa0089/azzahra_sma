@extends('layout.main')
@section('main', 'Data Barang Terhapus')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Barang yang Dihapus (Trash)</h4>
        <a href="{{ route('barang.index') }}" class="btn btn-primary btn-sm mb-3">Kembali ke Data Barang</a>
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
                <td>
                  <form action="{{ route('barang.restore', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success btn-sm">Restore</button>
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
@endsection
