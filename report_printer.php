<?php 
// 1. Taruh buffer dan session di paling atas untuk cegah error header
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Load file fungsi
require_once 'functions/function_printer.php';

// 3. Ambil data printer dari database
$all_printer = getAllPrinter();

// 4. Olah data untuk Chart (Hitung jumlah printer per lokasi)
$lokasi_counts = [];
foreach ($all_printer as $p) {
    $loc = $p['lokasi_printer'];
    if (!isset($lokasi_counts[$loc])) {
        $lokasi_counts[$loc] = 0;
    }
    $lokasi_counts[$loc]++;
}

// Urutkan dari yang terbanyak
arsort($lokasi_counts);

// Siapkan label dan data untuk Javascript
$labels = json_encode(array_keys($lokasi_counts));
$counts = json_encode(array_values($lokasi_counts));

// 5. Baru include header (HTML output dimulai di sini)
include_once 'layouts/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Printer Reports</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8"> <div class="card">
                <div class="card-body">
                    <h4 class="header-title">
                        SEBARAN PRINTER PER LOKASI - <?= strtoupper(date('F Y')); ?>
                    </h4>
                    <div dir="ltr">
                        <div id="basic-bar" class="apex-charts" data-colors="#39afd1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    var colors = ['#39afd1'];
    var dataColors = $('#basic-bar').data('colors');
    if (dataColors) {
        colors = dataColors.split(',');
    }

    var options = {
        chart: { 
            height: 380, 
            type: 'bar', 
            toolbar: { show: false } 
        },
        plotOptions: { 
            bar: { 
                horizontal: true,
                barHeight: '60%',
            } 
        },
        dataLabels: { 
            enabled: true,
            style: { colors: ['#fff'] }
        },
        // MENGGUNAKAN DATA DARI PHP
        series: [{ 
            name: 'Jumlah Printer',
            data: <?php echo $counts; ?> 
        }],
        colors: colors,
        xaxis: {
            // MENGGUNAKAN LABEL DARI PHP
            categories: <?php echo $labels; ?>,
        },
        states: { hover: { filter: 'none' } },
        grid: { borderColor: '#f1f3fa' },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " Unit"
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector('#basic-bar'), options);
    chart.render();
});
</script>