<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Laporan Barang Rusak</title>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      margin: 40px;
      color: #222;
      background-color: #fff;
    }
    .report-title {
  text-align: center;
  font-size: 24px;
  font-weight: 700;
  margin: 0.5px 0;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: #333;
  }
.report-title:last-of-type {
  margin-bottom: 30px;
}
    table.static {
      width: 100%;
      border-collapse: collapse;
      margin: 0 auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    table.static th,
    table.static td {
      border: 1px solid #666;
      padding: 10px 12px;
      text-align: center;
      vertical-align: middle;
      font-size: 14px;
    }
    table.static th {
      background-color: #f0f0f0;
      font-weight: 600;
      color: #444;
    }
    table.static tbody tr:nth-child(even) {
      background-color: #fafafa;
    }
    .text-end {
      text-align: right;
      padding-right: 15px;
    }
    .fw-bold {
      font-weight: 700;
    }
    /* Responsive & print-friendly */
    @media print {
      body {
        margin: 10mm 10mm 20mm 5mm;
        color-adjust: exact;
        -webkit-print-color-adjust: exact;
      }
      table.static th {
        background-color: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
      }
      /* Remove shadow for print */
      table.static {
        box-shadow: none;
      }
    }
  </style>
  <link rel="icon" href="{{ asset('images/favicon.png') }}">
</head>
<body>
  <div class="form-group">
    <p class="report-title">LAPORAN BARANG RUSAK INVENTARIS TIDAK HABIS PAKAI</p>
    <p class="report-title">BARANG-BARANG RUSAK INVENTARIS INDUK</p>
    <p class="report-title">TAHUN PELAJARAN .....</p>
    <table class="static" rules="all" border="1" >
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
                <th>Jumlah Rusak</th>
                <th>Gambar</th>
                <th>Tgl Rusak</th>
                <th>Keterangan</th>
                <th>Harga/Unit</th>
                <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($rusak as $item)
        @php
    $barang = $item->elektronik ?? $item->mobiler ?? $item->lainnya;
@endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
    <td>{{ $barang->kode_barang ?? '-' }}</td>
    <td>{{ $barang->nama_barang ?? '-' }}</td>
    <td>{{ $barang->merk ?? '-' }}</td>
    <td>{{ $barang->type ?? '-' }}</td>
    <td>{{ $barang->tgl_peroleh ?? '-' }}</td>
    <td>{{ $barang->asal_usul ?? '-' }}</td>
    <td>{{ $barang->cara_peroleh ?? '-' }}</td>
    <td>{{ $item->jumlah_brg_rusak }}</td>
    <td>
       @if(!empty($item->gambar_brg_rusak) && file_exists(public_path('gambar/' . $item->gambar_brg_rusak)))
    <img src="{{ asset('gambar/' . $item->gambar_brg_rusak) }}" alt="Gambar Rusak" width="80">
@else
    Tidak ada gambar
@endif
    </td>
    <td>{{ $item->tgl_rusak }}</td>
    <td>{{ $item->keterangan }}</td>
    <td>Rp {{ number_format($barang->harga_perunit ?? 0, 0, ',', '.') }}</td>
     <td>
  <span class="badge 
    @if($item->status == 'Berhasil Dimusnakan') bg-danger 
    @else bg-warning text-dark 
    @endif">
    {{ $item->status }}
  </span>
</td>
        </tr>
        @empty
        <tr>
          <td colspan="12" class="text-center">Tidak ada data Barang Rusak.</td>
        </tr>
        @endforelse
      </tbody>
    </table>

   <div style="width: 100%; margin-top: 50px;">
  <table style="width: 100%; font-size: 14px; border: none;">
    <tr>
      <td style="width: 50%; text-align: left;">
        <p>Mengetahui,</p>
        <p style="white-space: nowrap;">Kepala Sekolah SMA Islam Az-Zahra Palembang</p>
        <br><br><br><br>
        <p><u>____________________</u></p>
        <p>NIY: _________________</p>
      </td>
      <td style="width: 50%; text-align: right;">
        <p style="white-space: nowrap;">Palembang, ________________ 20__</p>
        <p>Waka Kurikulum dan Sapras</p>
        <br><br><br><br>
        <p><u>____________________</u></p>
        <p>NIY: _________________</p>
      </td>
    </tr>
  </table>
</div>

  </div>

<script>
  window.onload = function () {
    setTimeout(function () {
      window.print();
    }, 800);
  };
</script>

</body>
</html>
