@extends('layout.main')
@section('title', 'Tambah Barang Rusak')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Form Barang Rusak</h4>
        <p class="card-description">Formulir Barang Rusak</p>
        <form action="{{ route('rusak.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          {{-- Pilihan Jenis --}}
          <div class="form-group">
            <label for="jenis_brg_rusak">Jenis Barang</label>
            <select name="jenis_brg_rusak" id="jenis_brg_rusak" class="form-control" required>
              <option selected disabled>Pilih Jenis</option>
              <option value="elektronik">Elektronik</option>
              <option value="mobiler">Mobiler</option>
              <option value="lainnya">Lainnya</option>
            </select>
            @error('jenis_brg_rusak')
              <label class="text-danger">{{ $message }}</label>
            @enderror
          </div>

          {{-- Nama Barang Berdasarkan Jenis --}}
          <div class="form-group">
            <label for="nama_barang">Nama Barang</label>

            <select name="elektronik_id" id="elektronik_id" class="form-control barang-select" style="display:none;">
              <option selected disabled>Pilih Barang Elektronik</option>
              @foreach ($elektronik as $item)
                <option value="{{ $item->id }}"
                  data-kode="{{ $item->kode_barang }}"
                  data-merk="{{ $item->merk }}"
                  data-type="{{ $item->type }}"
                  data-tanggal="{{ $item->tgl_peroleh }}"
                  data-asal="{{ $item->asal_usul }}"
                  data-cara="{{ $item->cara_peroleh }}"
                  data-harga="{{ $item->harga_perunit }}">
                  {{ $item->nama_barang }}
                </option>
              @endforeach
            </select>

            <select name="mobiler_id" id="mobiler_id" class="form-control barang-select" style="display:none;">
              <option selected disabled>Pilih Barang Mobiler</option>
              @foreach ($mobiler as $item)
                <option value="{{ $item->id }}"
                  data-kode="{{ $item->kode_barang }}"
                  data-merk="{{ $item->merk }}"
                  data-type="{{ $item->type }}"
                  data-tanggal="{{ $item->tanggal_peroleh }}"
                  data-asal="{{ $item->asal_usul }}"
                  data-cara="{{ $item->cara_peroleh }}"
                  data-harga="{{ $item->harga_perunit }}">
                  {{ $item->nama_barang }}
                </option>
              @endforeach
            </select>

            <select name="lainnya_id" id="lainnya_id" class="form-control barang-select" style="display:none;">
              <option selected disabled>Pilih Barang Lainnya</option>
              @foreach ($lainnya as $item)
                <option value="{{ $item->id }}"
                  data-kode="{{ $item->kode_barang }}"
                  data-merk="{{ $item->merk }}"
                  data-type="{{ $item->type }}"
                  data-tanggal="{{ $item->tanggal_peroleh }}"
                  data-asal="{{ $item->asal_usul }}"
                  data-cara="{{ $item->cara_peroleh }}"
                  data-harga="{{ $item->harga_perunit }}">
                  {{ $item->nama_barang }}
                </option>
              @endforeach
            </select>

            @error('elektronik_id')
              <label class="text-danger">{{ $message }}</label>
            @enderror
            @error('mobiler_id')
              <label class="text-danger">{{ $message }}</label>
            @enderror
            @error('lainnya_id')
              <label class="text-danger">{{ $message }}</label>
            @enderror
          </div>

          {{-- Auto-filled Fields --}}
          <div class="form-group">
  <label for="kode_barang">Kode Barang</label>
  <select name="kode_barang" id="kode_barang" class="form-control">
    <option selected disabled>Pilih Kode Barang</option>
    @foreach($elektronik as $item)
      <option value="{{ $item->kode_barang }}">{{ $item->kode_barang }} - {{ $item->nama_barang }}</option>
    @endforeach
    @foreach($mobiler as $item)
      <option value="{{ $item->kode_barang }}">{{ $item->kode_barang }} - {{ $item->nama_barang }}</option>
    @endforeach
    @foreach($lainnya as $item)
      <option value="{{ $item->kode_barang }}">{{ $item->kode_barang }} - {{ $item->nama_barang }}</option>
    @endforeach
  </select>
</div>


          <div class="form-group">
            <label for="merk">Merk</label>
            <input type="text" id="merk" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label for="type">Type</label>
            <input type="text" id="type" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label for="tanggal_peroleh">Tanggal Peroleh</label>
            <input type="date" id="tanggal_peroleh" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label for="asal_usul">Asal Usul</label>
            <input type="text" id="asal_usul" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label for="cara_peroleh">Cara Peroleh</label>
            <input type="text" id="cara_peroleh" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label for="harga_perunit">Harga Per Unit</label>
            <input type="text" id="harga_perunit" class="form-control" readonly>
          </div>

          {{-- Form Tambahan --}}
          <div class="form-group">
            <label for="jumlah_brg_rusak">Jumlah Rusak</label>
            <input type="number" name="jumlah_brg_rusak" class="form-control" value="{{ old('jumlah_brg_rusak') }}" required>
            @error('jumlah_brg_rusak')
              <label class="text-danger">{{ $message }}</label>
            @enderror
          </div>

          <div class="form-group">
            <label for="gambar_brg_rusak">Gambar Barang Rusak</label>
            <input type="file" name="gambar_brg_rusak" class="form-control-file">
            @error('gambar_brg_rusak')
              <label class="text-danger">{{ $message }}</label>
            @enderror
          </div>

          <div class="form-group">
            <label for="tgl_rusak">Tanggal Rusak</label>
            <input type="date" name="tgl_rusak" class="form-control" value="{{ old('tgl_rusak') }}" required>
            @error('tgl_rusak')
              <label class="text-danger">{{ $message }}</label>
            @enderror
          </div>

          <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
            @error('keterangan')
              <label class="text-danger">{{ $message }}</label>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary mr-2">Simpan</button>
          <a href="{{ route('rusak.index') }}" class="btn btn-light">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Script --}}
<script>
  const jenisSelect = document.getElementById('jenis_brg_rusak');
  const selects = {
    elektronik: document.getElementById('elektronik_id'),
    mobiler: document.getElementById('mobiler_id'),
    lainnya: document.getElementById('lainnya_id')
  };

  const fields = {
    kode: document.getElementById('kode_barang'),
    merk: document.getElementById('merk'),
    type: document.getElementById('type'),
    tanggal: document.getElementById('tanggal_peroleh'),
    asal: document.getElementById('asal_usul'),
    cara: document.getElementById('cara_peroleh'),
    harga: document.getElementById('harga_perunit')
  };

  function resetAllSelects() {
    for (const key in selects) {
      selects[key].style.display = 'none';
      selects[key].selectedIndex = 0;
    }
    for (const key in fields) {
      fields[key].value = '';
    }
  }

  function onSelectChange(select) {
    const option = select.options[select.selectedIndex];
    if (option) {
      fields.kode.value = option.getAttribute('data-kode') || '';
      fields.merk.value = option.getAttribute('data-merk') || '';
      fields.type.value = option.getAttribute('data-type') || '';
      fields.tanggal.value = option.getAttribute('data-tanggal') || '';
      fields.asal.value = option.getAttribute('data-asal') || '';
      fields.cara.value = option.getAttribute('data-cara') || '';
      fields.harga.value = option.getAttribute('data-harga') || '';
    }
  }

  jenisSelect.addEventListener('change', function () {
    resetAllSelects();
    const selected = this.value;
    if (selects[selected]) {
      selects[selected].style.display = 'block';
    }
  });

  selects.elektronik.addEventListener('change', () => onSelectChange(selects.elektronik));
  selects.mobiler.addEventListener('change', () => onSelectChange(selects.mobiler));
  selects.lainnya.addEventListener('change', () => onSelectChange(selects.lainnya));
</script>
@endsection
