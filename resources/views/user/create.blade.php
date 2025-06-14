@extends('layout.main')
@section('title', 'Tambah User')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Manajemen User</h4>
                    <p class="card-description">
                        Tambahkan User
                    </p>
                    <form class="forms-sample" method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama User</label>
                            <input type="text" class="form-control" name="name" placeholder="Nama User" required>

                            <label for="email">Email User</label>
                            <input type="email" class="form-control" name="email" placeholder="Email User" required>

                            <label for="password">Password User</label>
                            <input type="text" class="form-control" name="password" placeholder="Password User" required>

                            <label for="role">Role</label>
                            <select class="form-control" name="role" id="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="A">Admin</option>
                                <option value="U">User</option>
                                <option value="K">Kepala Sekolah</option>
                                <option value="W">Wakil Kepala Sekolah</option>
                            </select>

                            <br>
                            @error('name')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
                            @error('email')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
                            @error('password')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
                            @error('role')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror


                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                        <a href="{{ url('user') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
