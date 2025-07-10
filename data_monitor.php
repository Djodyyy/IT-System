<?php 
    include_once 'layouts/header.php';
?>
                <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Inventory</li>
                                            <li class="breadcrumb-item">Komputer</li>
                                            <li class="breadcrumb-item active">Data Monitor</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><i class="uil-arrow-circle-right text-info"></i> Monitor</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->
                        <div class="row">
                            <div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title"><center>TABEL MONITOR</center></h4>
                                        <hr>
                                        <div class="row mb-2">
                                            <div class="col-sm-12">
                                                <div class="text-sm-end">
                                                    <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#add-komputer"><i class="mdi mdi-plus-circle-outline"></i> Create New</button>
                                                    <a href="export_komputer.php" type="button" class="btn btn-sm btn-dark mb-2">Export</a>
                                                </div>
                                            </div><!-- end col-->
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="small-table-preview">
                                                <div class="table-responsive-sm">
                                                    <table style="font-size: 12px;" id="inventory" class="table table-sm table-hover table-centered mb-0">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th>Kode Assets</th>
                                                                <th>Nama Assets</th>
                                                                <th>Tanggal Pembelian</th>
                                                                <th>User</th>
                                                                <th>Spec</th>
                                                                <th>Lokasi</th>
                                                                <th>Qty</th>
                                                                <th>Desc</th>
                                                                <th>Status</th>
                                                                <th>Detail</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>HDN-001</td>
                                                                <td>Monitor</td>
                                                                <td>27/01/2023</td>
                                                                <td>Bowo</td>
                                                                <td>LG 24 Inc</td>
                                                                <td><i style="font-size:14px" class="mdi mdi-map-marker-outline text-info""></i> IT</td>
                                                                <td>1</td>
                                                                <td>Unit</td>
                                                                <td><i class="mdi mdi-circle text-success"></i> Terpakai</td>
                                                                <td class="table-action">
                                                                    <a href="javascript: void(0);" class="action-icon"> <i class="dripicons-preview text-warning"></i></a>
                                                                </td>
                                                                <td class="table-action">
                                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-pencil text-primary"></i></a>
                                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-delete text-danger"></i></a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>HDN-002</td>
                                                                <td>Monitor</td>
                                                                <td>27/01/2023</td>
                                                                <td>Om Dodi</td>
                                                                <td>Acer 24 Inc</td>
                                                                <td><i style="font-size:14px" class="mdi mdi-map-marker-outline text-info""></i> IT</td>
                                                                <td>1</td>
                                                                <td>Unit</td>
                                                                <td><i class="mdi mdi-circle text-success"></i> Terpakai</td>
                                                                <td class="table-action">
                                                                    <a href="javascript: void(0);" class="action-icon"> <i class="dripicons-preview text-warning"></i></a>
                                                                </td>
                                                                <td class="table-action">
                                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-pencil text-primary"></i></a>
                                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-delete text-danger"></i></a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>HDN-003</td>
                                                                <td>Monitor</td>
                                                                <td>27/01/2023</td>
                                                                <td>-</td>
                                                                <td>LG 24 Inc</td>
                                                                <td><i style="font-size:14px" class="mdi mdi-map-marker-outline text-info""></i> IT</td>
                                                                <td>1</td>
                                                                <td>Unit</td>
                                                                <td><i class="mdi mdi-circle text-danger"></i> Rusak</td>
                                                                <td class="table-action">
                                                                    <a href="javascript: void(0);" class="action-icon"> <i class="dripicons-preview text-warning"></i></a>
                                                                </td>
                                                                <td class="table-action">
                                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-pencil text-primary"></i></a>
                                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-delete text-danger"></i></a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>HDN-004</td>
                                                                <td>Monitor</td>
                                                                <td>27/01/2023</td>
                                                                <td> - </td>
                                                                <td>LG 24 Inc</td>
                                                                <td><i style="font-size:14px" class="mdi mdi-map-marker-outline text-info""></i> IT</td>
                                                                <td>1</td>
                                                                <td>Unit</td>
                                                                <td><i class="mdi mdi-circle text-warning"></i> Stock</td>
                                                                <td class="table-action">
                                                                    <a href="javascript: void(0);" class="action-icon"> <i class="dripicons-preview text-warning"></i></a>
                                                                </td>
                                                                <td class="table-action">
                                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-pencil text-primary"></i></a>
                                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-delete text-danger"></i></a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div> <!-- end table-responsive-->                     
                                            </div> <!-- end preview-->
                                        </div> <!-- end tab-content-->

                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                                <!--  Add new task modal -->
                                <div class="modal fade task-modal-content" id="add-komputer" tabindex="-1" role="dialog" aria-labelledby="NewTaskModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="NewTaskModalLabel">Create New Data</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="POST" class="p-2" >
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="task-title" class="form-label">Kode Assets</label>
                                                                <input type="text" name="" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="task-priority2" class="form-label">Nama Assets</label>
                                                                <input type="text" name="" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="task-priority2" class="form-label">Tanggal Perakitan</label>
                                                                <input type="text" name="" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="task-title" class="form-label">User</label>
                                                                <input type="text" name="" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="task-priority2" class="form-label">IP</label>
                                                                <input type="text" name="" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="task-priority2" class="form-label">Spec</label>
                                                                <input type="text" name="" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="task-title" class="form-label">Lokasi</label>
                                                                <input type="number" name="" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="task-priority2" class="form-label">Qty</label>
                                                                <input type="number" name="" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label for="task-priority2" class="form-label">Desc</label>
                                                                <input type="text" name="" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label for="task-priority2" class="form-label">Keterangan</label>
                                                                <textarea type="text" name="" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <input type="hidden" name="">
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
