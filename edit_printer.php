<?php 
    include_once 'layouts/header_detail.php';
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
                                            <a href="data_printer.php" class="breadcrumb-item">Data Printer</a>
                                            <li class="breadcrumb-item active">Edit Printer</li>
                                        </ol>
                                    </div>
                                    <a href="data_printer.php"><h4 class="page-title"><i class="uil-arrow-circle-left text-info"></i> Back</h4></a>
                                </div>
                            </div>
                        </div>     
                        <div class="row">
                            <div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title"><center>FORM EDIT PRINTER</center></h4>
                                        <hr>
                                        <form action="functions/function_printer.php" method="POST" enctype="multipart/form-data" class="p-2" >
                                        <?php
                                            $id_printer = $_GET['id_printer'];
                                            $get  = showData($id_printer);
                                        ?>
                                        <?php foreach ($get as $data) { ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="task-title" class="form-label">Nama Printer</label>
                                                        <input type="text" name="nama_printer" class="form-control" value="<?= $data['nama_printer'] ?>">
                                                        <input type="hidden" name="id_printer" class="form-control" value="<?= $data['id_printer'] ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">IP</label>
                                                        <input type="text" name="ip_printer" class="form-control" value="<?= $data['ip_printer'] ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">Status</label>
                                                        <select name="status_printer" class="form-select mb-3">
                                                            <option value="<?= $data['status_printer']?>" selected><?php if($data['status_printer'] == 1){
                                                                echo "Active";
                                                             }elseif($data['status_printer'] == 0){
                                                                echo "Idle";
                                                             }else{
                                                                echo "Off";
                                                             } ?></option>
                                                            <option value="1">Active</option>
                                                            <option value="0">Idle</option>
                                                            <option value="2">Off</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">Tanggal</label>
                                                        <input type="text" name="tanggal_printer" class="form-control" value="<?= $data['tanggal_printer'] ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">Lokasi</label>
                                                        <input type="text" name="lokasi_printer" class="form-control" value="<?= $data['lokasi_printer'] ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>               
                                            <div class="text-end">
                                                <input type="hidden" name="edit">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <a href="data_printer.php" class="btn btn-dark">Cancel</a>
                                            </div>
                                        </form>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div>
                        <!-- end row-->                      
                    </div> <!-- container -->
                </div> <!-- content -->

<?php 
    include_once 'layouts/footer_detail.php';
?>