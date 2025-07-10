<?php 
    include_once 'layouts/header_detail.php';
    require 'functions/function_catrige.php';
?>
                <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Inventory</li>
                                            <li class="breadcrumb-item">Catrige</li>
                                            <a href="data_komputer.php" class="breadcrumb-item">Data Catrige</a>
                                            <li class="breadcrumb-item active">Edit Catrige</li>
                                        </ol>
                                    </div>
                                    <a href="data_catrige.php"><h4 class="page-title"><i class="uil-arrow-circle-left text-info"></i> Back</h4></a>
                                </div>
                            </div>
                        </div>     
                        <div class="row">
                            <div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title"><center>FORM EDIT CATRIGE</center></h4>
                                        <hr>
                                        <form action="functions/function_catrige.php" method="POST" enctype="multipart/form-data" class="p-2" >
                                        <?php
                                            $id_catrige   = $_GET['id_catrige'];
                                            $get  = showData($id_catrige);
                                        ?>
                                        <?php foreach ($get as $data) { ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="task-title" class="form-label">Nama Catrige</label>
                                                        <input type="text" name="nama_catrige" class="form-control" value="<?= $data['nama_catrige'] ?>">
                                                        <input type="hidden" name="id_catrige" class="form-control" value="<?= $data['id_catrige'] ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">Type</label>
                                                        <input type="text" name="type_catrige" class="form-control" value="<?= $data['type_catrige'] ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">Qty</label>
                                                        <input type="number" name="jml_catrige" class="form-control" value="<?= $data['jml_catrige'] ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>               
                                            <div class="text-end">
                                                <input type="hidden" name="edit">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <a href="data_catrige.php" class="btn btn-dark">Cancel</a>
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