@extends('layout.main')
@section('main', 'barang_rusak')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
       <div class="row mb-1">
          <div class="col">
            <h4 class="card-title ml-1">Data Barang Induk Rusak yang Dihapus (Trash)</h4>
          </div>
          <div class="col text-end d-flex align-items-end justify-content-end">
            <a href="{{ route('rusak.index') }}" class="btn btn-primary mdi mdi-arrow-left-bold btn-icon-prepend">
              Kembali ke Data Barang Induk Rusak
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
                <th>Jumlah Rusak</th>
                <th>Tgl Rusak</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
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
                <td>{{ $item->jumlah_brg_rusak }}</td>
                <td>{{ $item->tgl_rusak }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td>
                  <span class="badge 
                    @if($item->status == 'Berhasil Dimusnakan') bg-danger 
                    @else bg-warning text-dark 
                    @endif">
                    {{ $item->status }}
                  </span>
                </td>
                <td>{{ $item->deleted_at }}</td>
                <td>
                  <form action="{{ route('rusak.restore', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success btn-sm">Restore</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="11" class="text-center">Tidak ada data barang rusak yang terhapus.</td>
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
    toastr.success("{{ Session::get('success') }}");
  @endif
  @if (Session::get('error'))
    toastr.error("{{ Session::get('error') }}");
  @endif
</script>
@endsection
