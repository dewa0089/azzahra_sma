@extends('layout.main')
@section('main', 'history')

@section('content')

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <h4 class="card-title mb-4">History Kegiatan</h4>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('history.index') }}" class="mb-4">
          <div class="row g-2 align-items-center">
            <div class="col-auto">
              <label for="filter_period" class="col-form-label">Filter Periode:</label>
            </div>
            <div class="col-auto">
              <select name="filter_period" id="filter_period" class="form-select">
                <option value="">-- Semua --</option>
                <option value="daily" {{ request('filter_period') == 'daily' ? 'selected' : '' }}>Harian</option>
                <option value="monthly" {{ request('filter_period') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="yearly" {{ request('filter_period') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
              </select>
            </div>
            <div class="col-auto">
              <button type="submit" class="btn btn-primary">Terapkan</button>
            </div>
          </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
          <table class="table table-striped table-hover table-bordered">
            <thead class="thead-light">
              <tr>
                <th>User</th>
                <th>Action</th>
                <th>Description</th>
                <th>Time</th>
              </tr>
            </thead>
            <tbody>
              @forelse($histories as $history)
                <tr>
                  <td>{{ $history->user->name }}</td>
                  <td>{{ $history->action }}</td>
                  <td>{{ $history->description }}</td>
                  <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center">Tidak ada data Riwayat Kegiatan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Pagination --}}
        <div class="mr-5 mt-2 d-flex justify-content-end">
  {{ $histories->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>


      </div>
    </div>
  </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
@section('scripts')
<script>
    @if (Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif
</script>
@endsection
