@extends('dashboard.layout.master')

@section('content')

<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Siswa</li>
                    </ol>
                </div>
                <h4 class="page-title">Guru</h4>
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ $message }}
    </div>
    @endif
    <!-- end page title --> 
    <div class="mb-3">
    <a href="/admin/dashboard/guru/create" type="button" class="btn btn-success">Add New</a>
    <a href="/admin/dashboard/guru/export/" type="button" class="btn btn-success">Download Data</a>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Data Guru</h5>
            <div class="table-responsive">
                <table id="scroll-vertical-datatable" class="table dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nipd</th>
                            <th>Nama</th>
                            <th>Kelas Yang Diampuh</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($guru as $item)
                    <tr>
                        <td>
                            @if($item->profile_photo_path == null)
                            Belum ada foto
                            @else
                            <img src="{{ asset('storage/' . $item->profile_photo_path) }}" height="90px" width="100px" alt="Foto Profil Guru">
                            @endif
                        </td>
                        <td>{{$item->nipd}}</td>
                        <td>{{$item->nama}}</td>
                        <td>
                            @if($item->kelas->count() > 0)
                                @foreach($item->kelas as $kelas)
                                    {{$kelas->nama}}
                                @endforeach
                            @else
                                Tidak ada kelas
                            @endif
                        </td>
                        <td class="table-action">
                            <a href="" class="action-icon"> <i class="mdi mdi-eye"></i></a>
                            <a href="/admin/dashboard/guru/edit/{{$item->id}}" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                            <a href="javascript:void(0);" class="action-icon" onclick="showDeleteModal({{ $item->id }})"><i class="mdi mdi-delete"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
                                                    
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus guru ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showDeleteModal(guruId) {
        var deleteForm = document.getElementById('deleteForm');
        deleteForm.action = '/admin/dashboard/guru/delete/' + guruId;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
@endsection
