<?php 
    include_once 'layouts/header.php';
    require 'functions/function_user.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Data User</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="uil-arrow-circle-right text-info"></i> User
                </h4>
            </div>
        </div>
    </div>     

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-center">TABEL USER</h4>
                    <hr>

                    <div class="row mb-2">
                        <div class="col-sm-12 text-end">
                            <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#add-user">
                                <i class="mdi mdi-plus-circle-outline"></i> Create New
                            </button>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane show active" id="small-table-preview">
                            <div class="table-responsive-sm">
                                <table style="font-size: 12px;" id="scroll-horizontal-datatable" class="table table-sm w-100 nowrap">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nama</th>
                                            <th width="300px">Username</th>
                                            <th width="150px">Role</th>
                                            <th width="80px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $all = getData(); 
                                        foreach ($all as $data) { ?>
                                            <tr>
                                                <td><?= htmlspecialchars($data['nama']) ?></td>
                                                <td><?= htmlspecialchars($data['username']) ?></td>
                                                <td>
                                                    <?php 
                                                        if($data['role'] == '1') echo '<span class="badge badge-danger-lighten">Admin</span>';
                                                        elseif($data['role'] == '2') echo '<span class="badge badge-info-lighten">User</span>';
                                                        elseif($data['role'] == '3') echo '<span class="badge badge-primary-lighten">Petugas Peminjaman</span>';
                                                        else echo '<span class="badge badge-secondary-lighten">Viewer</span>';
                                                    ?>
                                                </td>
                                                <td class="table-action">
                                                    <a href="edit_user.php?id_user=<?= $data['id_user']; ?>" class="action-icon">
                                                        <i class="mdi mdi-pencil text-primary"></i>
                                                    </a>
                                                    <a href="functions/function_user.php?hapus=<?= $data['id_user']; ?>" class="action-icon" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                        <i class="mdi mdi-delete text-danger"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>                
                        </div>
                    </div>

                </div> </div> <div class="modal fade task-modal-content" id="add-user" tabindex="-1" aria-labelledby="NewTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Create New Data</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="functions/function_user.php" method="POST" class="p-2">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama</label>
                                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" placeholder="Username untuk login" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Role</label>
                                        <select name="role" class="form-select">
                                            <option value="1">Admin</option>
                                            <option value="2">User</option>
                                            <option value="3">Petugas Peminjaman</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <input type="hidden" name="add">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div> </div> </div> <?php include_once 'layouts/footer.php'; ?>