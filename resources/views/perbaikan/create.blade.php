@extends('layout.main')
@section('title', 'Tambah Perbaikan')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Perbaikan Barang</h4>
                    <p class="card-description">
                        Formulir Perbaikan
                    </p>
<form class="forms-sample" method="POST" action="{{ route('perbaikan.selesaikan.store') }}">
    @csrf
    <input type="hidden" name="rusak_id" value="{{ request('rusak_id') }}">

    <div class="form-group">
        <label for="tanggal_perbaikan">Tanggal Perbaikan</label>
        <input type="date" class="form-control" name="tanggal_perbaikan" required>

        <label for="jumlah_perbaikan">Jumlah Diperbaiki</label>
        <input type="number" class="form-control" name="jumlah_perbaikan"
               value="{{ $rusak->jumlah_brg_rusak }}" readonly>

       <label for="biaya_perbaikan">Biaya Perbaikan</label>
<input type="text" class="form-control" id="biaya_perbaikan_view" placeholder="Rp. 0"
       value="{{ old('biaya_perbaikan') ? 'Rp. ' . number_format(old('biaya_perbaikan'), 0, ',', '.') : '' }}">
<input type="hidden" name="biaya_perbaikan" id="biaya_perbaikan" value="{{ old('biaya_perbaikan') }}">

        <label for="keterangan">Keterangan</label>
        <input type="text" class="form-control" name="keterangan" value="-">

        @error('jumlah_perbaikan')
            <label class="text-danger">{{ $message }}</label>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary mr-2">Simpan</button>
    <a href="{{ url('perbaikan') }}" class="btn btn-light">Batal</a>
</form>
                </div>
            </div>
        </div>
    </div>

<script>
    // Format angka ke format rupiah
    function formatRupiah(angka) {
        return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    const biayaView = document.getElementById('biaya_perbaikan_view');
    const biayaHidden = document.getElementById('biaya_perbaikan');

    biayaView.addEventListener('input', function () {
        const angka = this.value.replace(/[^0-9]/g, '');
        biayaHidden.value = angka;
        this.value = formatRupiah(angka);
    });

    // Saat halaman dimuat
    window.addEventListener('DOMContentLoaded', () => {
        const angka = biayaHidden.value;
        if (angka) {
            biayaView.value = formatRupiah(angka);
        }
    });
</script>



@endsection
