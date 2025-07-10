<?php 
    session_start();
    include_once 'functions/function_komputer.php';
    include_once 'layouts/header.php';

    $totalKomputer = getTotalKomputer();
    $totalPerbaikan = getTotalPerbaikan();
?>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <form class="d-flex">
                    <a href="index.php" class="btn btn-primary ms-2">
                        <i class="mdi mdi-autorenew"></i>
                    </a>
                </form>
            </div>
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <!-- Total Komputer -->
    <div class="col-lg-6">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="mdi mdi-desktop-tower widget-icon"></i>
                </div>
                <h5 class="text-muted fw-normal mt-0">Total Komputer</h5>
                <h3 class="mt-3 mb-3"><?= $totalKomputer ?> pcs</h3>
                <p class="mb-0 text-muted">
                    <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> +%</span>
                    <span class="text-nowrap">Update terbaru</span>  
                </p>
            </div>
        </div>
    </div>

    <!-- Perbaikan Komputer -->
    <div class="col-lg-6">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="mdi mdi-tools widget-icon text-warning"></i>
                </div>
                <h5 class="text-muted fw-normal mt-0">Perbaikan Komputer</h5>
                <h3 class="mt-3 mb-3"><?= $totalPerbaikan ?></h3>
                <p class="mb-0 text-muted">
                    <span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i> -%</span>
                    <span class="text-nowrap">Update terbaru</span>
                </p>
            </div>
        </div>
    </div>
</div>

</div> <!-- close .content -->
</div> <!-- close .content-page -->

<?php 
    include_once 'layouts/footer.php';

    if (isset($_SESSION['message']) && $_SESSION['message'] == 'logfail') { 
        $namesaya = $_SESSION['username'];
        echo "
            <script>
                $.toast({
                    heading: 'Login Berhasil!',
                    text: 'Selamat Datang $namesaya',
                    position: 'top-right',
                    hideAfter: 3500,
                    textAlign: 'center',
                    icon: 'success'
                });
            </script>
        ";
    }
    unset($_SESSION['message']);
?>
