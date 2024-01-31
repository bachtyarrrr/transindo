@extends('layout.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Official Travel</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Official Travel</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Sewa Mobil</h3>
                    <button type="button" class="btn btn-primary text-white float-sm-right" data-toggle="modal"
                        data-target="#addModal">Pengembalian Mobil</button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Plat</th>
                                <th>Tanggal Kembali</th>
                                <th>Jumlah Hari</th>
                                <th>Biaya Sewa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($pengembalians as $p)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $p->nomor_plat }}</td>
                                <td>{{ $p->tanggal_kembali }}</td>
                                <td>{{ $p->jumlah_hari }}</td>
                                <td>{{ $p->biaya_sewa }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Pengembalian Mobil</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            <form action="/proses-pengembalian" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="card card-default">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nomor Plat Mobil <code>*</code></label>
                                        <select class="form-select @error('nomor_plat') is-invalid @enderror"
                                            id="nomor_plat" name="nomor_plat" required>
                                            @foreach($mobil as $m)
                                            <option value="{{ $m->nomor_plat }}">{{ $m->merek }} - {{ $m->nomor_plat }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Kembali <code>*</code></label>
                                        <input type="date"
                                            class="form-control @error('tanggal_kembali') is-invalid @enderror"
                                            id="tanggal_kembali" name="tanggal_kembali" required>
                                        @error('tanggal_kembali')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobil_id">Pilih Mobil <code>*</code></label>
                                        <input type="number"
                                            class="form-control @error('jumlah_hari') is-invalid @enderror"
                                            id="jumlah_hari" name="jumlah_hari" required>
                                        @error('jumlah_hari')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection