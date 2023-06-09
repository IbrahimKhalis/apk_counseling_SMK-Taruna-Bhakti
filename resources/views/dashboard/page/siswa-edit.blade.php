@extends('dashboard.layout.master')

@section('content')
    <div class="container-fluid">
        @if($errors->any())
                <div class="mt-2 alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <form action="/admin/dashboard/siswa/edit/{{$siswa->id}}'" method="post" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label" for="validationCustom01">Nisn</label>
                <input type="text" name="nisn" value="{{$siswa->nisn}}" class="form-control" id="validationCustom01" placeholder="First name"
                    required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="validationCustom02">Nama</label>
                <input type="text" name="nama" value="{{$siswa->nama}}" class="form-control" id="validationCustom02" placeholder="Last name"
                    required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>

            <div class="mb-3">
                <label for="example-select" class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select" id="example-select">
                    @foreach($kelas as $kelas)
                    <option value="{{ $kelas->id }}" @if($kelas->id === $siswa->kelas_id) selected @endif>{{ $kelas->nama }}</option>
                @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                    <option value="Pria" @if($siswa->jenis_kelamin === 'Pria') selected @endif>Pria</option>
                    <option value="perempuan" @if($siswa->jenis_kelamin === 'perempuan') selected @endif>Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="example-date" for="validationCustom02" class="form-label">Tanggal Lahir</label>
                <input class="form-control" value="{{ $siswa->tanggal_lahir }}" id="validationCustom01" id="example-date" type="date" name="tanggal_lahir" required>
            </div>

            {{-- <div class="mb-3">
                <label class="form-label" for="validationCustom02">Email</label>
                <input type="text" name="email" value="{{ $siswa->user->email }}" class="form-control" id="validationCustom02" placeholder="Email" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div> --}}

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group input-group-merge">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
                    <div class="input-group-text" data-password="false">
                        <span class="password-eye"></span>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">SAVE</button>
        </form>


    </div>
@endsection
