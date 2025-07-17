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
                <td class="d-flex gap-2">
                <form action="{{ route('rusak.restore', $item->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <button type="submit" class="btn btn-success btn-sm mr-2">Restore</button>
                </form>
                <form action="{{ route('rusak.forceDelete', $item->id) }}" method="POST">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
