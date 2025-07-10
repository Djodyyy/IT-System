<?php 
    include_once 'layouts/header_detail.php';
    require 'functions/function_user.php';
?>
                <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">User</li>
                                            <a href="data_user.php" class="breadcrumb-item">Data User</a>
                                            <li class="breadcrumb-item active">Edit User</li>
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
                                        <h4 class="header-title"><center>FORM EDIT USER</center></h4>
                                        <hr>
                                        <form action="functions/function_user.php" method="POST" enctype="multipart/form-data" class="p-2" >
                                        <?php
                                            $id_user   = $_GET['id_user'];
                                            $get  = showData($id_user);
                                        ?>
                                        <?php foreach ($get as $data) { ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="task-title" class="form-label">Nama</label>
                                                        <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>">
                                                        <input type="hidden" name="id_user" class="form-control" value="<?= $data['id_user'] ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">Username</label>
                                                        <input type="text" name="username" class="form-control" value="<?= $data['username'] ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">Role</label>
                                                        <input type="text" name="role" class="form-control" value="<?= $data['role'] ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="task-priority2" class="form-label">Password</label>
                                                        <input type="password" name="password" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>               
                                            <div class="text-end">
                                                <input type="hidden" name="edit">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <a href="data_user.php" class="btn btn-dark">Cancel</a>
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