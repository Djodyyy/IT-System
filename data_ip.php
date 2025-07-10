<?php 
    include_once 'layouts/header.php';
    require 'functions/function_ip.php';
?>
                <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item active">IP Address</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><i class="uil-arrow-circle-right text-info"></i> IP Address</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->
                        <div class="row">
                            <div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title"><center>TABEL IP ADDRESS</center></h4>
                                        <hr>
                                        <div class="row mb-2">
                                            <div class="col-sm-12">
                                                <div class="text-sm-end">
                                                    <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#add-ip"><i class="mdi mdi-plus-circle-outline"></i> Create New</button>
                                                    <a href="" type="button" class="btn btn-sm btn-dark mb-2">Export</a>
                                                </div>
                                            </div><!-- end col-->
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="small-table-preview">
                                                <div class="table-responsive-sm">
                                                    <table style="font-size: 12px;" id="scroll-horizontal-datatable" class="table table-sm w-100 nowrap">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th style="text-align:center;" width="40px">No</th>
                                                                <th style="text-align:center;">Ip Address</th>
                                                                <th style="text-align:center;" width="300px">Used</th>
                                                                <th style="text-align: center;" width="100px">Status</th>
                                                                <th style="text-align: center;" width="100px">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $all = getData(); $no = 1; ?>
                                                            <?php foreach ($all as $data) { ?>
                                                            <tr>
                                                                <td style="text-align:center;"><?= $no++ ?></td>
                                                                <td style="text-align:center;"><?= $data['ip_address'] ?></td>
                                                                <td style="text-align:center;"><?= $data['used_ip'] ?></td>
                                                                <td style="text-align:center;"><?= $data['status_ip'] ?></td>
                                                                <td style="text-align: center;" class="table-action">
                                                                    <a href="<?= 'edit_ip.php?id_catrige='.$data['id_ip'];?>" class="action-icon"> <i class="mdi mdi-pencil text-primary"></i></a>
                                                                    <a href="<?= 'functions/function_ip.php?hapus='.$data['id_ip']; ?>" class="action-icon"> <i class="mdi mdi-delete text-danger"></i></a>
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
                                <div class="modal fade task-modal-content" id="add-ip" tabindex="-1" role="dialog" aria-labelledby="NewTaskModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="NewTaskModalLabel">Create New Data</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="functions/function_ip.php" method="POST" class="p-2" >
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label for="task-title" class="form-label">IP Address</label>
                                                                <input type="text" name="ip_address" class="form-control" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label for="task-priority2" class="form-label">Used</label>
                                                                <input type="text" name="used_ip" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label for="task-priority2" class="form-label">Status</label>
                                                                <input type="text" name="status_ip" class="form-control">
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