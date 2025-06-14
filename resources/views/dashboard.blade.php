@extends('layout.main')
@section('main', 'Dashboard')

@section('content')
<h2>Dashboard</h2>
<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="row w-100 flex-grow">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">DATA PEMINJAMAN DAN PENGEMBALIAN</p>
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <div class="d-flex justify-content-between traffic-status">
                                    <div class="item">
                                        <p class="mb-">Jumlah Peminjaman</p>
                                        <h5 class="font-weight-bold mb-0">{{ $countPeminjaman }}</h5>
                                        <div class="color-border"></div>
                                    </div>
                                    <div class="item">
                                        <p class="mb-">Jumlah Pengembalian</p>
                                        <h5 class="font-weight-bold mb-0">{{ $countPengembalian }}</h5>
                                        <div class="color-border"></div>
                                    </div>
                                    <div class="item">
                                        <p class="mb-">Total Peminjaman & Pengembalian</p>
                                        <h5 class="font-weight-bold mb-0">{{ $countPeminjaman + $countPengembalian }}</h5>
                                        <div class="color-border"></div>
                                    </div>
                                </div>
                            </div>
                   <div class="col-md-5">
    <ul class="nav nav-pills nav-pills-custom justify-content-md-end">
        <li class="nav-item">
            <a class="nav-link {{ $filter == 'semua' ? 'active' : '' }}" href="{{ route('dashboard', ['filter' => 'semua']) }}">
                Semua
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $filter == '6bulan' ? 'active' : '' }}" href="{{ route('dashboard', ['filter' => '6bulan']) }}">
                6 Bulan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $filter == '1tahun' ? 'active' : '' }}" href="{{ route('dashboard', ['filter' => '1tahun']) }}">
                1 Tahun
            </a>
        </li>
    </ul>
</div>
                        </div>
<canvas id="peminjamanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Grafik Area & Doughnut --}}
<div class="row">
        <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">DATA BARANG INVENTARIS SEKOLAH</h4>
                <canvas id="doughnutChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">DATA BARANG INVENTARIS RUSAK & HILANG SEKOLAH</h4>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Doughnut Chart - Barang
    const ctx = document.getElementById('doughnutChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Elektronik', 'Mobiler', 'Lainnya'],
            datasets: [{
                label: 'Jumlah Barang',
                data: [
                    {{ $countElektronik ?? 0 }},
                    {{ $countMobiler ?? 0 }},
                    {{ $countLainnya ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)', // biru
                    'rgba(255, 206, 86, 0.7)', // kuning
                    'rgba(75, 192, 192, 0.7)'  // hijau
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                }
            }
        }
    });

    // Bar Chart - Peminjaman & Pengembalian
    const ctxPeminjaman = document.getElementById('peminjamanChart').getContext('2d');
    new Chart(ctxPeminjaman, {
        type: 'bar',
        data: {
            labels: ['Peminjaman', 'Pengembalian'],
            datasets: [{
                label: 'Jumlah',
                data: [{{ $countPeminjaman }}, {{ $countPengembalian }}],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)', // merah
                    'rgba(54, 162, 235, 0.7)'  // biru
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Statistik Peminjaman & Pengembalian'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Doughnut Chart - Barang Rusak dan Dimusnahkan
const ctxPie = document.getElementById('pieChart').getContext('2d');
new Chart(ctxPie, {
    type: 'pie', // ubah dari 'pie' jadi 'doughnut'
    data: {
        labels: ['Barang Rusak', 'Barang Dimusnahkan'],
        datasets: [{
            data: [{{ $countRusak ?? 0 }}, {{ $countPemusnahan ?? 0 }}],
            backgroundColor: [
                'rgba(255, 159, 64, 0.7)',  // orange
                'rgba(75, 192, 192, 0.7)'   // hijau muda
            ],
            borderColor: [
                'rgba(255, 159, 64, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top' // sama seperti doughnut chart pertama
            },
            title: {
                display: false // sembunyikan judul agar seragam
            }
        }
    }
});

</script>

@endsection
