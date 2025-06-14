<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Laporan Barang Elektronik</title>
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
        margin: 10mm 15mm 10mm 15mm;
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
    <p class="report-title">LAPORAN BARANG INVENTARIS TIDAK HABIS PAKAI</p>
    <p class="report-title">BARANG-BARANG ELEKTRONIK</p>
    <p class="report-title">TAHUN PELAJARAN .....</p>
    <table class="static" rules="all" border="1" >
      <thead>
        <tr>
          <th>No</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th>Merk</th>
          <th>Type</th>
          <th>Tanggal Peroleh Barang</th>
          <th>Asal Usul</th>
          <th>Cara Perolehan</th>
          <th>Jumlah Barang</th>
          <th>Harga Per Unit Barang</th>
          <th>Total Harga</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($elektronik as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>                
          <td>{{ $item['kode_barang'] }}</td>
          <td>{{ $item['nama_barang'] }}</td>
          <td>{{ $item['merk'] }}</td>
          <td>{{ $item['type'] }}</td>
          <td>{{ $item['tgl_peroleh'] }}</td>
          <td>{{ $item['asal_usul'] }}</td>
          <td>{{ $item['cara_peroleh'] }}</td>
          <td>{{ $item['jumlah_brg'] }}</td>
          <td>Rp {{ number_format($item['harga_perunit'], 0, ',', '.') }}</td>
          <td>Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="11" class="text-center">Tidak ada data Barang Elektronik.</td>
        </tr>
        @endforelse
        @if(count($elektronik) > 0)
        <tr>
          <td colspan="9" class="text-end fw-bold">Total Keseluruhan</td>
          <td colspan="2" class="fw-bold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
        </tr>
        @endif
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
