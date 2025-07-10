<?php 
    include_once 'layouts/header_detail.php';
    require 'functions/function_catrige.php';
?>
                <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-1">
                                <div class="page-title-box">
                                    <a href="data_catrige.php"><h4 class="page-title"><i class="uil-arrow-circle-left text-info"></i> Back</h4></a>
                                </div>
                            </div>
                            <div class="col-11">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Inventory</li>
                                            <li class="breadcrumb-item">Printer</li>
                                            <a href="data_catrige.php" class="breadcrumb-item">Data Catrige</a>
                                            <li class="breadcrumb-item active">Detail Catrige</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>     
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-3"><center>Form Penambahan</center></h4>
                                        <hr>
                                        <form action="functions/function_catrige.php" method="POST">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">ID Catrige</label>
                                                        <?php
                                                            $id_catrige   = $_GET['id_catrige'];
                                                            $get  = showData($id_catrige);
                                                            foreach ($get as $data) { 
                                                            $stock = $data['jml_catrige'];
                                                            ?>
                                                        <input type="text" id="simpleinput" class="form-control" value="<?= $data['nama_catrige']; ?>" readonly>
                                                        <input type="hidden" name="id_catrige" id="simpleinput" class="form-control" value="<?= $data['id_catrige'] ?>">
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Tanggal</label>
                                                        <input type="text" name="tanggal_catrige" class="form-control" value="<?= date('d M Y')?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Jumlah</label>
                                                        <input type="number" name="jumlah_catrige" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <input type="hidden" name="add1">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                            <div style="font-size: 11px;" class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-3"><center>Riwayat Penambahan</center></h4>
                                        <hr>
                                        <div class="row mb-2">
                                            <div class="col-sm-12">
                                                <div class="text-sm-end">
                                                    <a href="" type="button" class="btn btn-sm btn-dark mb-2">Export</a>
                                                </div>
                                            </div><!-- end col-->
                                        </div>
                                        <table id="scroll-horizontal-datatable" class="table table-sm w-100 nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Name Catrige</th>
                                                    <th width="100px">Jumlah Penambahan</th>
                                                    <th width="100px">Tanggal</th>
                                                    <th width="50px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $all = getCatrige(); $no = 1; ?>
                                                <?php foreach ($all as $data) { ?>
                                                <tr>
                                                    <td><?= $data['nama_catrige'] ?></td>
                                                    <td><?= $data['jumlah_catrige'] ?></td>
                                                    <td><?= $data['tanggal_catrige'] ?></td>
                                                    <td style="text-align: center;" class="table-action">
                                                        <a href="<?= 'functions/function_catrige.php?hapus1='.$data['id_add_catrige']; ?> & id_catrige=<?= $_GET['id_catrige'];?>" class="action-icon"> <i class="ion-icon"> <i class="mdi mdi-delete text-danger"></i></a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->                      
                    </div> <!-- container -->
                </div> <!-- content -->
<?php 
    include_once 'layouts/footer_detail.php';
?>
 