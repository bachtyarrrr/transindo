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
                    <h3 class="card-title">Data Mobil</h3>
                    <button type="button" class="btn btn-primary text-white float-sm-right" data-toggle="modal"
                        data-target="#addModal">Tambah Mobil</button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Merk</th>
                                <th>Model</th>
                                <th>Nomor Plat</th>
                                <th>Tarif Sewa / Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($mobil as $m)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $m->merek }}</td>
                                <td>{{ $m->model }}</td>
                                <td>{{ $m->nomor_plat }}</td>
                                <td>{{ $m->tarif_sewa }}</td>
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
                <h4 class="modal-title">Form Tambah Mobil</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/proses_tambah" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="card card-default">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Merek Mobil <code>*</code></label>
                                        <input type="text" class="form-control" id="merek"
                                            placeholder="Masukkan merk mobil" name="merek">
                                    </div>
                                    <div class="form-group">
                                        <label>Model Mobil <code>*</code></label>
                                        <input type="text" class="form-control" id="model"
                                            placeholder="Masukkan model mobil" name="model">
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No Plat Mobil <code>*</code></label>
                                        <input type="text" class="form-control" id="nomor_plat"
                                            placeholder="Masukkan No Plat mobil" name="nomor_plat">
                                    </div>
                                    <div class="form-group">
                                        <label>Tarif Sewa per Hari (Rp) <code>*</code></label>
                                        <input type="number" class="form-control" id="tarif_sewa"
                                            placeholder="Masukkan tarif sewa" name="tarif_sewa">
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