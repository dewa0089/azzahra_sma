@extends('layout.main')
@section('main', 'manajemen')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row mb-1">
          <div class="col">
            <h4 class="card-title ml-1">Data User yang Dihapus (Trash)</h4>
          </div>
          <div class="col text-end d-flex align-items-end justify-content-end">
            <a href="{{ route('user.index') }}" class="btn btn-primary mdi mdi-arrow-left-bold btn-icon-prepend">
              Kembali ke Manajemen User
            </a>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>ID User</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($user as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>
                  @switch($item->role)
                      @case('A') Admin @break
                      @case('U') User @break
                      @case('K') Kepala Sekolah @break
                      @default Tidak Diketahui
                  @endswitch
                </td>
                <td>{{ $item->deleted_at }}</td>
                <td class="d-flex gap-2">
                <form action="{{ route('user.restore', $item->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <button type="submit" class="btn btn-success btn-sm mr-2">Restore</button>
                </form>
                <form action="{{ route('user.forceDelete', $item->id) }}" method="POST">
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
                <td colspan="7" class="text-center">Tidak ada user yang terhapus.</td>
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
