@extends('layout.main')
@section('main', 'manajemen')

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
         <div class="row mb-1">
          <div class="col">
            <h4 class="card-title">Manajemen User</h4>
          </div>
          <div class="col text-end d-flex align-items-end justify-content-end">
            <a href="{{ route('user.create') }}" class="btn btn-success mdi mdi-upload btn-icon-prepend">
              Tambah User
            </a>
          </div>
        </div>        
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>
                  No
                </th>
                <th>
                  ID User
                </th>
                <th>
                  Nama User
                </th>
                <th>
                  Email
                </th>
                <th>
                  Password
                </th>
                <th>
                  Role
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($user as $item)
              <tr>
                 <td>{{ $loop->iteration }}</td>                
                  <td>{{ $item['id'] }}</td>
                  <td>{{ $item['name'] }}</td>
                  <td>{{ $item['email'] }}</td>
                  <td>{{ $item['password'] }}</td>
                  <td>
                    @switch($item['role'])
                        @case('A')
                            Admin
                            @break
                        @case('U')
                            User
                            @break
                        @case('K')
                            Kepala Sekolah
                            @break
                        @case('W')
                            Wakil Kepala Sekolah
                            @break
                        @default
                            Tidak Diketahui
                    @endswitch
                </td>

                  <td>
                      <div class="d-flex justify-content-center">
                        <a href="{{ route('user.edit', $item->id) }}">
                          <button class="btn btn-success btn-sm mx-3">Edit</button>
                      </a>
                      <form method="POST" action="{{ route('user.destroy', $item->id) }}">
                          @method('delete')
                          @csrf
                          <button type="submit" class="btn btn-danger btn-sm show_confirm"
                              data-toggle="tooltip" title='Delete'
                              data-nama='{{ $item->name }}'>Hapus Data</button>
                      </form>
                          </form>
                      </div>


                  </td>
              </tr>
          @endforeach
             
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
    <script>
        @if (Session::get('success'))
            toastr.success("{{ Session::get('success') }}")
        @endif
    </script>
@endsection
