@extends('layout.main')
@section('main', 'pemusnaan')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row mb-1">
          <div class="col">
            <h4 class="card-title">Pemusnahan Barang</h4>
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
                <th>Tanggal Peroleh Barang</th>
                <th>Tanggal & Jam Input Pemusnaan</th>
                <th>Tanggal Rusak</th>
                <th>Tanggal Pemusnaan</th>
                <th>Asal Usul</th>
                <th>Cara Perolehan</th>
                <th>Jumlah dimusnakan</th>
                <th>Gambar Barang Rusak</th>
                <th>Gambar Pemusnaan Barang</th>
                <th>Harga/Unit</th>
                <th>Total Harga</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pemusnaan as $item)
              @php
                  $barang = $item->rusak->elektronik ?? $item->rusak->mobiler ?? $item->rusak->lainnya;
              @endphp
              <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $barang->kode_barang ?? '-' }}</td>
                  <td>{{ $barang->nama_barang ?? '-' }}</td>
                  <td>{{ $barang->merk ?? '-' }}</td>
                  <td>{{ $barang->type ?? '-' }}</td>
                  <td>{{ $barang->tgl_peroleh ?? '-' }}</td>
                  <td>{{ $item['created_at'] }}</td>
                  <td>{{ $item->rusak->tgl_rusak }}</td>
                  <td>{{ $item->tanggal_pemusnaan ?? '-' }}</td>
                  <td>{{ $barang->asal_usul ?? '-' }}</td>
                  <td>{{ $barang->cara_peroleh ?? '-' }}</td>
                  <td>{{ $item->jumlah_pemusnaan }}</td>
                  <td>
                     @if(!empty($item->rusak->gambar_brg_rusak) && file_exists(public_path('gambar/' . $item->rusak->gambar_brg_rusak)))
                        <img src="{{ asset('gambar/' . $item->rusak->gambar_brg_rusak) }}" alt="Gambar Rusak" width="80">
                    @else
                        Tidak ada gambar
                    @endif
                  </td>
                  <td>
                      @if($item->gambar_pemusnaan)
                          <img src="{{ asset('gambar/' . $item->gambar_pemusnaan) }}" width="80">
                      @else
                          Tidak ada gambar
                      @endif
                  </td>
                  <td>Rp {{ number_format($barang->harga_perunit ?? 0, 0, ',', '.') }}</td>
                  <td>Rp {{ number_format(($barang->harga_perunit ?? 0) * $item->jumlah_pemusnaan, 0, ',', '.') }}</td>
                  <td>{{ $item->keterangan ?? '-' }}</td>
              </tr>
               @empty
  <tr>
    <td colspan="17" class="text-center">Tidak ada data Pemusnaan.</td>
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
</script>
@endsection
