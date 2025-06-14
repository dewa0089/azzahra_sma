@extends('layout.main')
@section('title', 'Edit User')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Barang</h4>
                    <p class="card-description">
                         Barang Non-Inventaris
                    </p>
                    <form class="forms-sample" method="POST" action="{{ route('user.update', $user->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="id">ID User</label>
                            <input type="text" class="form-control" name="id" placeholder="ID User"
                                value="{{ $user->id }}" readonly>

                            <label for="name">Nama User</label>
                            <input type="text" class="form-control" name="name" placeholder="Nama User"
                                value="{{ $user->name }}">

                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email"
                                value="{{ $user->email }}">

                            <label for="password">Password</label>
                            <input type="text" class="form-control" name="password" placeholder="Password">

                            <label for="role">Role</label>
                            <select class="form-control" name="role" id="role">
                                <option value="">-- Pilih Role --</option>
                                <option value="A" {{ $user->role == 'A' ? 'selected' : '' }}>Admin</option>
                                <option value="U" {{ $user->role == 'U' ? 'selected' : '' }}>User</option>
                                <option value="K" {{ $user->role == 'K' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="W" {{ $user->role == 'W' ? 'selected' : '' }}>Wakil Kepala Sekolah</option>
                            </select>


                            <br>
                            @error('id')
                                <label class="text-danger">{{ $message }}</label>
                            @enderror
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


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
