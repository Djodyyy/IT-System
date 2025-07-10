<?php 
    include_once 'layouts/header.php';
    require 'functions/function_printer.php';
?>
                <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Printer</li>
                                            <li class="breadcrumb-item active">Data Printer</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><i class="uil-arrow-circle-right text-info"></i> Printer</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->
                        <div class="row">
                            <div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title"><center>TABEL PRINTER</center></h4>
                                        <hr>
                                        <div class="row mb-2">
                                            <div class="col-sm-12">
                                                <div class="text-sm-end">
                                                    <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#add-printer"><i class="mdi mdi-plus-circle-outline"></i> Create New</button>
                                                    <a href="export_printer.php" type="button" class="btn btn-sm btn-dark mb-2">Export</a>
                                                </div>
                                            </div><!-- end col-->
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="small-table-preview">
                                                <div class="table-responsive-sm">
                                                    <table style="font-size: 12px;" id="scroll-horizontal-datatable" class="table table-sm w-100 nowrap">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Printer</th>
                                                                <th>IP</th>
                                                                <th>Lokasi</th>
                                                                <th>Status</th>
                                                                <th>Tanggal</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $all = getData(); $no = 1; ?>
                                                            <?php foreach ($all as $data) { ?>
                                                            <tr>
                                                                <td><?= $no++ ?></td>
                                                                <td><?= $data['nama_printer'] ?></td>
                                                                <td><?= $data['ip_printer'] ?></td>
                                                                <td><i style="font-size:14px" class="mdi mdi-map-marker-outline text-info"></i><?= $data['lokasi_printer'] ?></td>
                                                                <td><?php if($data['status_printer'] == 1){
                                                                    echo "Aktiv";
                                                                }elseif($data['status_printer'] == 0){
                                                                    echo "Idle";
                                                                }else{
                                                                    echo "Off";
                                                                } ?></td>
                                                                <td><?= $data['tanggal_printer'] ?></td>
                                                                <td class="table-action">
                                                                    <a href="<?= 'edit_printer.php?id_printer='.$data['id_printer'];?>" class="action-icon"> <i class="mdi mdi-pencil text-primary"></i></a>
                                                                    <a href="<?= 'functions/function_printer.php?hapus='.$data['id_printer']; ?>" class="action-icon"> <i class="mdi mdi-delete text-danger"></i></a>
                                                                </td>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div> <!-- end table-responsive-->                     
                                            </div> <!-- end preview-->
                                        </div> <!-- end tab-content-->

                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                                <!--  Add new task modal -->
                                <div class="modal fade task-modal-content" id="add-printer" tabindex="-1" role="dialog" aria-labelledby="NewTaskModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="NewTaskModalLabel">Create New Data</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="functions/function_printer.php" method="POST" class="p-2" >
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="task-title" class="form-label">Nama Printer</label>
                                                                <input type="text" name="nama_printer" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="task-priority2" class="form-label">IP</label>
                                                                <input type="text" name="ip_printer" class="form-control" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="task-title" class="form-label">Status</label>
                                                                <select name="status_printer" class="form-select mb-3">
                                                                    <option selected>Open this select menu</option>
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Idle</option>
                                                                    <option value="2">Off</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="task-priority2" class="form-label">Tanggal</label>
                                                                <input type="text" name="tanggal_printer" class="form-control" value=" <?= date('d M Y'); ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="task-priority2" class="form-label">Lokasi</label>
                                                                <input type="text" name="lokasi_printer" class="form-control" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <input type="hidden" name="add">
                                                        <button type="sumbit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->
                            </div><!-- end col-->
                        </div>
                        <!-- end row-->                        
                    </div> <!-- container -->
                </div> <!-- content -->
<?php 
    include_once 'layouts/footer.php';
?>
