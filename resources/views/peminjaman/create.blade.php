@extends('layout.main')
@section('title', 'Tambah Peminjaman')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Peminjaman Barang</h4>
                <p class="card-description">Formulir Pengajuan</p>
                <form action="{{ route('peminjaman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nama_peminjam">Nama Peminjam</label>
                        <input type="text" class="form-control" name="nama_peminjam" 
                        value="{{ Auth::user()->name }}" readonly>

                        <label for="barang_id">Nama Barang</label>
                        <select name="barang_id" id="barang_id" class="form-control">
                            <option selected disabled>Pilih</option>
                            @foreach ($barang as $item)
                                <option value="{{ $item->id }}"
                                        data-kode="{{ $item->kode_barang }}"
                                        data-nama="{{ $item->nama_barang }}">
                                    {{ $item->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <label class="text-danger">{{ $message }}</label>
                        @enderror

                        <label for="kode_barang">Kode Barang</label>
                        <input type="text" class="form-control" id="kode_barang" name="kode_barang" readonly>
                        @error('kode_barang')
                            <label class="text-danger">{{ $message }}</label>
                        @enderror

                        <label for="jumlah_peminjam">Jumlah Peminjaman</label>
                        <input type="number" class="form-control" name="jumlah_peminjam" placeholder="Jumlah Peminjaman" value="{{ old('jumlah_peminjam') }}">
                        @error('jumlah_peminjam')
                            <label class="text-danger">{{ $message }}</label>
                        @enderror

                        <label for="tgl_peminjam">Tanggal Peminjaman</label>
                        <input type="date" class="form-control" name="tgl_peminjam" value="{{ old('tgl_peminjam') }}">
                        @error('tgl_peminjam')
                            <label class="text-danger">{{ $message }}</label>
                        @enderror
                        <br>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- CDN jQuery dan toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

{{-- JS Toastr --}}
<script>
    $(document).ready(function() {
        @if (Session::get('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif

        @if ($errors->has('stok'))
            toastr.error("{{ $errors->first('stok') }}");
        @endif
    });

    // Auto isi kode barang
    $('#barang_id').on('change', function () {
        const selected = $(this).find('option:selected');
        const kode = selected.data('kode');
        $('#kode_barang').val(kode || '');
    });
</script>
@endsection
