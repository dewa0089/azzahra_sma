@extends('layout.main')
@section('title', 'Edit Mobiler')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Barang Mobiler</h4>
                    <p class="card-description">
                        Inventaris Barang Mobiler
                    </p>
                    <form class="forms-sample" method="POST" action="{{ route('mobiler.update', $mobiler->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="kode_barang">Kode Barang</label>
                            <input type="text" class="form-control" name="kode_barang" placeholder="Kode Barang"
                                value="{{ $mobiler->kode_barang }}">

                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" placeholder="Nama Barang"
                                value="{{ $mobiler->nama_barang }}">

                            <label for="merk">Merk Barang</label>
                            <input type="text" class="form-control" name="merk" placeholder="Merk Barang"
                                value="{{ $mobiler->merk }}">

                            <label for="type">Type Barang</label>
                            <input type="text" class="form-control" name="type" placeholder="Type Barang"
                                value="{{ $mobiler->type }}">

                            <label for="tgl_peroleh">Tanggal Peroleh Barang</label>
                            <input type="date" class="form-control" name="tgl_peroleh" placeholder="Tanggal Peroleh Barang"
                                value="{{ $mobiler->tgl_peroleh }}">

                            <select class="form-control" name="asal_usul">
                                <option value="">-- Pilih Asal Usul --</option>
                                <option value="Pembelian" {{ $elektronik->asal_usul == 'Pembelian' ? 'selected' : '' }}>Pembelian</option>
                                <option value="Hibah" {{ $elektronik->asal_usul == 'Hibah' ? 'selected' : '' }}>Hibah</option>
                                <option value="Sumbangan" {{ $elektronik->asal_usul == 'Sumbangan' ? 'selected' : '' }}>Sumbangan</option>
                                <option value="Bantuan Pemerintah" {{ $elektronik->asal_usul == 'Bantuan Pemerintah' ? 'selected' : '' }}>Bantuan Pemerintah</option>
                            </select>

                            <select class="form-control" name="cara_peroleh">
                                <option value="">-- Pilih Cara Peroleh --</option>
                                <option value="Toko Langsung" {{ $elektronik->cara_peroleh == 'Toko Langsung' ? 'selected' : '' }}>Toko Langsung</option>
                                <option value="Lelang" {{ $elektronik->cara_peroleh == 'Lelang' ? 'selected' : '' }}>Lelang</option>
                                <option value="Donatur" {{ $elektronik->cara_peroleh == 'Donatur' ? 'selected' : '' }}>Donatur</option>
                                <option value="Dinas Pendidikan" {{ $elektronik->cara_peroleh == 'Dinas Pendidikan' ? 'selected' : '' }}>Dinas Pendidikan</option>
                            </select>
                            
                            <label for="jumlah_brg">Jumlah Barang</label>
                            <input type="number" class="form-control" id="jumlah_brg" name="jumlah_brg" placeholder="Jumlah Barang"
                                value="{{ $mobiler->jumlah_brg }}">

                            <label for="harga_perunit">Harga Per Unit Barang</label>
                            <input type="text" class="form-control" id="harga_perunit_view" placeholder="Harga Per Unit Barang" 
                                value="{{ number_format($mobiler->harga_perunit, 0, ',', '.') }}">

                            <input type="hidden" id="harga_perunit" name="harga_perunit" value="{{ $mobiler->harga_perunit }}">

                            <label for="total_harga">Total Harga Barang</label>
                            <input type="text" class="form-control" id="total_harga_view" placeholder="Total Harga Barang" 
                                value="{{ number_format($mobiler->total_harga, 0, ',', '.') }}" readonly>

                            <input type="hidden" id="total_harga" name="total_harga" value="{{ $mobiler->total_harga }}">

                            <br>
                            @error('kode_barang')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
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
                        <a href="{{ url('mobiler') }}" class="btn btn-light">Batal</a>
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
