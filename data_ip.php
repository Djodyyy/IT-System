<?php
include_once 'layouts/header.php';
require_once 'functions/function_ip.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">IP Address</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="uil-arrow-circle-right text-info"></i> IP Address
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div>
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-center mb-3">TABEL IP ADDRESS</h4>
                    <hr>

                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="text-sm-end">
                                <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#add-ip">
                                    <i class="mdi mdi-plus-circle-outline"></i> Create New
                                </button>
                                <a href="" type="button" class="btn btn-sm btn-dark mb-2">Export</a>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane show active" id="small-table-preview">
                            <div class="table-responsive-sm">
                                <table id="scroll-horizontal-datatable" class="table table-sm table-bordered w-100 nowrap" style="font-size: 12px;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="text-align:center;" width="40px">No</th>
                                            <th style="text-align:center;">IP Address</th>
                                            <th style="text-align:center;" width="300px">Used</th>
                                            <th style="text-align:center;" width="100px">Status</th>
                                            <th style="text-align:center;" width="100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $all = getAllIp();
                                        $no = 1;
                                        if (!empty($all)) {
                                            foreach ($all as $data) {
                                                $conflict = findIpByAddress($data['ip_address'], $data['id_ip']);
                                        ?>
                                                <tr>
                                                    <td style="text-align:center;"><?= $no++ ?></td>
                                                    <td style="text-align:center;"><?= htmlspecialchars($data['ip_address']) ?></td>
                                                    <td style="text-align:center;"><?= htmlspecialchars($data['user_ip']) ?></td>
                                                    <td style="text-align:center;">
                                                        <?php if (strtolower($data['status_ip']) === 'aktif') : ?>
                                                            <span class="badge bg-success">Aktif</span>
                                                        <?php else : ?>
                                                            <span class="badge bg-secondary"><?= htmlspecialchars($data['status_ip']) ?></span>
                                                        <?php endif; ?>

                                                        <?php if ($conflict) : ?>
                                                            <span class="badge bg-danger ms-1"
                                                                title="Dipakai juga oleh: <?= htmlspecialchars($conflict['user_ip']) ?>">
                                                                Conflict
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td style="text-align: center;" class="table-action">
                                                        <a href="<?= 'edit_ip.php?id_ip=' . $data['id_ip']; ?>" class="action-icon">
                                                            <i class="mdi mdi-pencil text-primary"></i>
                                                        </a>
                                                        <a href="<?= 'functions/function_ip.php?hapus=' . $data['id_ip']; ?>"
                                                            class="action-icon"
                                                            onclick="return confirm('Yakin ingin menghapus data ini?');">
                                                            <i class="mdi mdi-delete text-danger"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='5' class='text-center text-muted'>Belum ada data IP Address</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ==================== MODAL TAMBAH IP ==================== -->
            <div class="modal fade task-modal-content" id="add-ip" tabindex="-1" role="dialog" aria-labelledby="NewTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="NewTaskModalLabel">Create New Data</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="functions/function_ip.php" method="POST" class="p-2">
                                <div class="mb-3">
                                    <label for="ip_address" class="form-label">IP Address</label>
                                    <input type="text" name="ip_address" id="ip_address" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="user_ip" class="form-label">Used (User)</label>
                                    <input type="text" name="user_ip" id="user_ip" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="status_ip" class="form-label">Status</label>
                                    <input type="text" name="status_ip" id="status_ip" class="form-control">
                                </div>

                                <div class="text-end">
                                    <input type="hidden" name="add" value="1">
                                    <button type="submit" class="btn btn-primary submit-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include_once 'layouts/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function showIpWarning(container, message) {
            let warn = container.querySelector('.ip-warning');
            if (!warn) {
                warn = document.createElement('div');
                warn.className = 'ip-warning mt-2 text-danger small';
                container.appendChild(warn);
            }
            warn.textContent = message;
        }

        function clearIpWarning(container) {
            let warn = container.querySelector('.ip-warning');
            if (warn) warn.remove();
        }

        document.querySelectorAll('form').forEach(function(form) {
            const ipInput = form.querySelector('input[name="ip_address"]');
            const saveBtn = form.querySelector('.submit-btn');

            if (!ipInput) return;

            ipInput.addEventListener('blur', function() {
                const ip = ipInput.value.trim();
                if (ip === "") {
                    clearIpWarning(form);
                    saveBtn.disabled = false;
                    return;
                }

                
                fetch('functions/check_ip_ajax.php?ip=' + encodeURIComponent(ip))
                    .then(r => r.json())
                    .then(data => {
                        if (!data.ok) {
                            showIpWarning(form, 'Gagal mengecek IP!');
                            saveBtn.disabled = false;
                            return;
                        }
                        if (data.exists) {
                            showIpWarning(form, 'IP sudah dipakai oleh: ' + (data.user_ip || '-'));
                            saveBtn.disabled = true;
                        } else {
                            clearIpWarning(form);
                            saveBtn.disabled = false;
                        }
                    })
                    .catch(e => {
                        showIpWarning(form, 'Error memeriksa server!');
                        saveBtn.disabled = false;
                    });
            });
        });

    });
</script>