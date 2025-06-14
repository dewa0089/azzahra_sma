<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Laporan Barang Pengembalian</title>
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
    <p class="report-title">LAPORAN PENGEMBALIAN BARANG INVENTARIS HABIS PAKAI</p>
    <p class="report-title">PENGEMBALIAN BARANG-BARANG PENDUKUNG</p>
    <p class="report-title">TAHUN PELAJARAN .....</p>
    <table class="static" rules="all" border="1" >
      <thead>
        <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah Peminjaman</th>
                <th>Tanggal Batas Pengembalian</th>
                <th>Jumlah Barang Baik</th>
                <th>Jumlah Barang Rusak</th>
                <th>Jumlah Barang Hilang</th>
                <th>Tanggal Pengembalian</th>
                <th>Keterangan</th>
                <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($pengembalian as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>                
                <td>{{ $item->peminjaman->nama_peminjam }}</td>
                <td>{{ $item->peminjaman->barang->kode_barang }}</td>
                <td>{{ $item->peminjaman->barang->nama_barang }}</td>
                <td>{{ $item->peminjaman->jumlah_peminjam }}</td>
                <td>{{ $item->peminjaman->tgl_kembali }}</td>
                <td>{{ $item->jumlah_brg_baik ?? '-' }}</td>
                <td>{{ $item->jumlah_brg_rusak ?? '-' }}</td>
                <td>{{ $item->jumlah_brg_hilang ?? '-' }}</td>
                <td>{{ $item->tgl_pengembalian ?? '-' }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td>
                <span class="badge 
                  @if($item->status == 'Dikembalikan') bg-success 
                  @elseif($item->status == 'Belum Dikembalikan') bg-warning text-dark 
                  @elseif($item->status == 'Menunggu Persetujuan') bg-warning text-dark 
                  @else bg-secondary 
                  @endif">
                  {{ $item->status }}
                </span>
                </td>
        </tr>
        @empty
        <tr>
          <td colspan="11" class="text-center">Tidak ada data Barang Pengembalian.</td>
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
