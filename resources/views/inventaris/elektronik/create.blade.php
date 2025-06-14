@extends('layout.main')
@section('title', 'Tambah Barang')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Inventaris Barang Elektronik</h4>
                    <p class="card-description">
                        Tambah Barang Elektronik
                    </p>
                    <form class="forms-sample" method="POST" action="{{ route('elektronik.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">   
                            <label for="kode_barang">Kode Barang</label>
                            <input type="text" class="form-control" name="kode_barang" placeholder="Kode Barang"
                                value="{{ old('kode_barang') }}">
                            @error('kode_barang')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror

                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" placeholder="Nama Barang"
                                value="{{ old('nama_barang') }}">
                            @error('nama_barang')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror

                            <label for="merk">Merk Barang</label>
                            <input type="text" class="form-control" name="merk" placeholder="Merk Barang"
                                value="{{ old('merk') }}">
                            @error('merk')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror

                            <label for="type">Type Barang</label>
                            <input type="text" class="form-control" name="type" placeholder="Type Barang"
                                value="{{ old('type') }}">
                            @error('type')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror

                            <label for="tgl_peroleh">Tanggal Peroleh Barang</label>
                            <input type="date" class="form-control" name="tgl_peroleh" value="{{ old('tgl_peroleh') }}">
                            @error('tgl_peroleh')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror

                            <label for="asal_usul">Asal Usul Barang</label>
                            <input type="text" class="form-control" name="asal_usul" placeholder="Asal Usul Barang" value="{{ old('asal_usul') }}">
                            @error('asal_usul')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror

                            <label for="cara_peroleh">Cara Peroleh Barang</label>
                            <input type="text" class="form-control" name="cara_peroleh" placeholder="Cara Peroleh Barang" value="{{ old('cara_peroleh') }}">
                            @error('cara_peroleh')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror

                            <label for="jumlah_brg">Jumlah Barang</label>
                            <input type="number" class="form-control" name="jumlah_brg" placeholder="Jumlah Barang" value="{{ old('jumlah_brg') }}" id="jumlah_brg">
                            @error('jumlah_brg')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror

                        <label for="harga_perunit_view">Harga Per Unit Barang</label>
                        <input type="text" class="form-control" id="harga_perunit_view" placeholder="Rp. 0" value="{{ old('harga_perunit') ? 'Rp. ' . number_format(old('harga_perunit'), 0, ',', '.') : '' }}">
                        <input type="hidden" name="harga_perunit" id="harga_perunit" value="{{ old('harga_perunit') }}">
                        @error('harga_perunit')
                            <label class="text-danger">{{ $message }}</label>
                        @enderror

                        <label for="total_harga_view">Total Harga Barang</label>
                        <input type="text" class="form-control" id="total_harga_view" placeholder="Rp. 0" readonly>
                        <input type="hidden" name="total_harga" id="total_harga" value="{{ old('total_harga') }}">
                        @error('total_harga')
                            <label class="text-danger">{{ $message }}</label>
                        @enderror

                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                        <a href="{{ url('elektronik') }}" class="btn btn-light">Batal</a>
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
        const numeric = this.value.replace(/[^0-9]/g, '');
        hargaPerUnit.value = numeric;
        this.value = formatRupiah(numeric);
        updateTotalHarga();
    });

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
