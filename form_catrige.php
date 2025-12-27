<?php 
    include_once 'layouts/header.php';
    require 'functions/function_form.php';
?>
                <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Printer</li>
                                            <li class="breadcrumb-item active">Data Pengambilan</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><i class="uil-arrow-circle-right text-info"></i> Form Pengambilan</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->
                        <div class="row">
                            <div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title"><center>FORM PENGAMBILAN</center></h4>
                                        <hr>
                                        <form action="functions/function_form.php" method="POST" class="p-2" >
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">Type Catrige</label>
                                                        <select name="id_catrige" class="form-select mb-3">
                                                        <?php $all = getCatrige(); foreach ($all as $data) { ?>
                                                            <option value="<?= $data['id_catrige'] ?>"><?= $data['type_catrige'] ?> ( <?= $data['jml_catrige'] ?> )</option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">Departemen</label>
                                                        <input type="text" name="depart" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">Tanggal</label>
                                                        <input type="text" name="tanggal_form" class="form-control" value="<?= date('d M Y'); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">Qty</label>
                                                        <input type="number" name="qty_pengambilan" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <input type="hidden" name="add">
                                                <button type="sumbit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div>
                        <div class="row">
                            <div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title"><center>RIWAYAT PENGAMBILAN CATRIGE</center></h4>
                                        <hr>
                                        <div class="row mb-2">
                                            <div class="col-sm-12">
                                                <div class="text-sm-end">                                                    
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
                                                                <th>No</th>
                                                                <th>Type Catrige</th>
                                                                <th>Departemen</th>
                                                                <th>Tanggal</th>
                                                                <th width="80px">Qty</th>
                                                                <th style="text-align: center;" width="70px">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $all = getData(); $no = 1; ?>
                                                            <?php foreach ($all as $data) { ?>
                                                            <tr>
                                                                <td><?= $no++ ?></td>
                                                                <td><?= $data['type_catrige'] ?></td>
                                                                <td><?= $data['depart'] ?></td>
                                                                <td><?= $data['tanggal_form'] ?></td>
                                                                <td><?= $data['qty_pengambilan'] ?></td>
                                                                <td style="text-align: center;" class="table-action">
                                                                    <a href="<?= 'edit_form.php?id_form='.$data['id_form'];?>" class="action-icon"> <i class="mdi mdi-pencil text-primary"></i></a>
                                                                    <a href="<?= 'functions/function_form.php?hapus='.$data['id_form']; ?>" class="action-icon"> <i class="mdi mdi-delete text-danger"></i></a>
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
                            </div><!-- end col-->
                        </div>
                        <!-- end row-->                        
                    </div> <!-- container -->
                </div> <!-- content -->
<?php 
    include_once 'layouts/footer.php';
?>