@extends('layout.main')
@section('title', 'Edit Lainnya')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Barang Lainnya</h4>
                    <p class="card-description">
                        Inventaris Barang Lainnya
                    </p>
                    <form class="forms-sample" method="POST" action="{{ route('lainnya.update', $lainnya->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="kode_barang">Kode Barang</label>
                            <input type="text" class="form-control" name="kode_barang" placeholder="Kode Barang"
                                value="{{ $lainnya->kode_barang }}">

                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" placeholder="Nama Barang"
                                value="{{ $lainnya->nama_barang }}">

                            <label for="merk">Merk Barang</label>
                            <input type="text" class="form-control" name="merk" placeholder="Merk Barang"
                                value="{{ $lainnya->merk }}">

                            <label for="type">Type Barang</label>
                            <input type="text" class="form-control" name="type" placeholder="Type Barang"
                                value="{{ $lainnya->type }}">

                            <label for="tgl_peroleh">Tanggal Peroleh Barang</label>
                            <input type="date" class="form-control" name="tgl_peroleh" placeholder="Tanggal Peroleh Barang"
                                value="{{ $lainnya->tgl_peroleh }}">

                            <label for="asal_usul">Asal Usul Barang</label>
                            <input type="text" class="form-control" name="asal_usul" placeholder="Asal Usul Barang"
                                value="{{ $lainnya->asal_usul }}">

                            <label for="cara_peroleh">Cara Peroleh Barang</label>
                            <input type="text" class="form-control" name="cara_peroleh" placeholder="Cara Peroleh Barang"
                                value="{{ $lainnya->cara_peroleh }}">
                            
                            <label for="jumlah_brg">Jumlah Barang</label>
                            <input type="number" class="form-control" id="jumlah_brg" name="jumlah_brg" placeholder="Jumlah Barang"
                                value="{{ $lainnya->jumlah_brg }}">

                            <label for="harga_perunit">Harga Per Unit Barang</label>
                            <input type="text" class="form-control" id="harga_perunit_view" placeholder="Harga Per Unit Barang" 
                                value="{{ number_format($lainnya->harga_perunit, 0, ',', '.') }}">

                            <input type="hidden" id="harga_perunit" name="harga_perunit" value="{{ $lainnya->harga_perunit }}">

                            <label for="total_harga">Total Harga Barang</label>
                            <input type="text" class="form-control" id="total_harga_view" placeholder="Total Harga Barang" 
                                value="{{ number_format($lainnya->total_harga, 0, ',', '.') }}" readonly>

                            <input type="hidden" id="total_harga" name="total_harga" value="{{ $lainnya->total_harga }}">

                            <br>
                            @error('nama_barang')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
                            @error('merk')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
                            @error('type')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
                            @error('tgl_peroleh')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
                            @error('asal_usul')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
                            @error('cara_peroleh')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
                            @error('jumlah_brg')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
                            @error('harga_perunit')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
                            @error('total_harga')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror

                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                        <a href="{{ url('lainnya') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const hargaPerUnitView = document.getElementById('harga_perunit_view');
        const hargaPerUnit = document.getElementById('harga_perunit');
        const jumlahBrg = document.getElementById('jumlah_brg');
        const totalHargaView = document.getElementById('total_harga_view');
        const totalHarga = document.getElementById('total_harga');

        // Format angka ke format Rupiah
        function formatRupiah(angka) {
            return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Fungsi menghitung dan update total harga
        function updateTotalHarga() {
            const harga = parseInt(hargaPerUnit.value) || 0;
            const jumlah = parseInt(jumlahBrg.value) || 0;
            const total = harga * jumlah;
            totalHarga.value = total;
            totalHargaView.value = formatRupiah(total);
        }

        // Saat user menginput harga per unit (input view)
        hargaPerUnitView.addEventListener('input', function () {
            const numeric = this.value.replace(/[^0-9]/g, '');  // hanya angka
            hargaPerUnit.value = numeric;
            this.value = formatRupiah(numeric);
            updateTotalHarga();
        });

        // Saat user menginput jumlah barang
        jumlahBrg.addEventListener('input', updateTotalHarga);

        // Inisialisasi saat halaman dimuat
        window.addEventListener('DOMContentLoaded', () => {
            const harga = parseInt(hargaPerUnit.value) || 0;
            hargaPerUnitView.value = formatRupiah(harga);
            updateTotalHarga();
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
