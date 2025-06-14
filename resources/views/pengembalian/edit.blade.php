@extends('layout.main')
@section('title', 'Edit Pengembalian')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pengembalian Barang</h4>
                <p class="card-description">
                    Pengajuan Pengembalian Barang
                </p>
                <form class="forms-sample" method="POST" action="{{ route('pengembalian.update', $pengembalian->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Informasi peminjaman (readonly) --}}
                    <div class="form-group">
                        <label for="nama_peminjam">Nama Peminjam</label>
                        <input type="text" class="form-control" value="{{ $pengembalian->peminjaman->nama_peminjam }}" readonly>

                        <label for="kode_barang">Kode Barang</label>
                        <input type="text" class="form-control" value="{{ $pengembalian->peminjaman->barang->kode_barang }}" readonly>

                        <label for="nama_barang">Nama Barang</label>
                        <input type="text" class="form-control" value="{{ $pengembalian->peminjaman->barang->nama_barang }}" readonly>

                        <label for="jumlah_peminjam">Jumlah Peminjaman</label>
                        <input type="text" class="form-control" value="{{ $pengembalian->peminjaman->jumlah_peminjam }}" readonly>

                        <label for="tgl_kembali">Tanggal Batas Pengembalian</label>
                        <input type="date" class="form-control" value="{{ $pengembalian->peminjaman->tgl_kembali }}" readonly>
                    </div>

                    {{-- Field yang bisa diisi --}}
                    <div class="form-group">
                        <label for="jumlah_brg_baik">Jumlah Barang Baik</label>
                        <input type="number" class="form-control" name="jumlah_brg_baik" value="{{ old('jumlah_brg_baik', $pengembalian->jumlah_brg_baik) }}" required>
                        @error('jumlah_brg_baik')
                            <label class="text-danger">{{ $message }}</label>
                        @enderror

                        <label for="jumlah_brg_rusak">Jumlah Barang Rusak</label>
                        <input type="number" class="form-control" name="jumlah_brg_rusak" value="{{ old('jumlah_brg_rusak', $pengembalian->jumlah_brg_rusak) }}" required>
                        @error('jumlah_brg_rusak')
                            <label class="text-danger">{{ $message }}</label>
                        @enderror

                        <label for="jumlah_brg_hilang">Jumlah Barang Hilang</label>
                        <input type="number" class="form-control" name="jumlah_brg_hilang" value="{{ old('jumlah_brg_hilang', $pengembalian->jumlah_brg_hilang) }}" required>
                        @error('jumlah_brg_hilang')
                            <label class="text-danger">{{ $message }}</label>
                        @enderror

                        <label for="tgl_pengembalian">Tanggal Pengembalian</label>
                        <input type="date" class="form-control" name="tgl_pengembalian" value="{{ old('tgl_pengembalian', $pengembalian->tgl_pengembalian) }}" required>
                        @error('tgl_pengembalian')
                            <label class="text-danger">{{ $message }}</label>
                        @enderror

                         <label for="keterangan">Keterangan</label>
                        <input type="text" class="form-control" name="keterangan" value="{{ old('keterangan', $pengembalian->keterangan) }}">
                        @error('keterangan')
                            <label class="text-danger">{{ $message }}</label>
                        @enderror

                        @if ($errors->has('total_pengembalian'))
    <div class="alert alert-danger mt-2">
        {{ $errors->first('total_pengembalian') }}
    </div>
@endif
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                    <a href="{{ route('pengembalian.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
