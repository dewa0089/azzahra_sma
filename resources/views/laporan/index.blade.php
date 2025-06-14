@extends('layout.main')
@section('main', 'laporan')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title text-center">Cetak Laporan</h4>

        <form id="laporanForm" method="GET" action="#" target="_blank">
          <div class="form-group">
            <label for="laporan">Pilih Jenis Laporan</label>
            <select class="form-control" id="laporan" name="laporan" required>
              <option value="" disabled selected>-- Pilih Laporan --</option>
              <option value="/laporan/elektronik">Laporan Inventaris Barang Induk Elektronik</option>
              <option value="/laporan/mobiler">Laporan Inventaris Barang Induk Mobiler</option>
              <option value="/laporan/lainnya">Laporan Inventaris Barang Induk Lainnya</option>
              <option value="/laporan/barangKecil">Laporan Inventaris Barang Pendukung</option>
              <option value="/laporan/peminjaman">Laporan Inventaris Peminjaman Barang Pendukung</option>
              <option value="/laporan/pengembalian">Laporan Inventaris Pengembalian Barang Pendukung</option>
              <option value="/laporan/rusak">Laporan Inventaris Barang Induk Rusak</option>
              <option value="/laporan/pemusnaan">Laporan Inventaris Pemusnahan Barang Induk</option>
            </select>
          </div>

          <div class="form-group mt-3">
            <label for="filter">Filter Waktu</label>
            <select class="form-control" id="filter" name="filter" required>
              <option value="semua" selected>Semua</option>
              <option value="bulan">Bulan Ini</option>
              <option value="tahun">Tahun Ini</option>
            </select>
          </div>

          <div class="form-group mt-3">
  <label for="format">Pilih Format</label>
  <select class="form-control" id="format" name="format" required>
    <option value="pdf" selected>PDF</option>
    <option value="word">Word</option>
  </select>
</div>


          <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Cetak</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.getElementById('laporanForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const laporan = document.getElementById('laporan').value;
  const filter = document.getElementById('filter').value;
  const format = document.getElementById('format').value;

  if (!laporan) {
    toastr.error("Silakan pilih jenis laporan terlebih dahulu.");
    return;
  }

  const url = `${laporan}?filter=${filter}&format=${format}`;
  window.open(url, '_blank');
});


  @if (Session::get('success'))
    toastr.success("{{ Session::get('success') }}")
  @endif
</script>
@endsection
