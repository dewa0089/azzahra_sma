@extends('layout.main')
@section('title', 'Tambah Pemusnaan')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Pemusnaan Barang</h4>
                    <p class="card-description">
                        Formulir Pemusnaan
                    </p>
<form class="forms-sample" method="POST" action="{{ route('pemusnaan.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="rusak_id" value="{{ request('rusak_id') }}">

    <div class="form-group">
        <label for="tanggal_pemusnaan">Tanggal Pemusnaan</label>
        <input type="date" class="form-control" name="tanggal_pemusnaan" required>

       <label for="jumlah_pemusnaan">Jumlah Dimusnahkan</label>
<input type="number" class="form-control" name="jumlah_pemusnaan"
       value="{{ $rusak->jumlah_brg_rusak }}" readonly>


        <label for="gambar_pemusnaan">Gambar Pemusnaan</label>
        <input type="file" class="form-control" name="gambar_pemusnaan">

        <label for="keterangan">Keterangan</label>
        <input type="text" class="form-control" name="keterangan">

        @error('jumlah_pemusnaan')
            <label class="text-danger">{{ $message }}</label>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary mr-2">Simpan</button>
    <a href="{{ url('pemusnaan') }}" class="btn btn-light">Batal</a>
</form>
                </div>
            </div>
        </div>
    </div>


@endsection
