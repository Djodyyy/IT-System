<?php 
    include_once 'layouts/header.php';
    require 'functions/function_printer.php';
?>

    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Bar Charts</h4>
                </div>
            </div>
        </div><!-- end page title --> 
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">PENGGUNAAN CATRIGE BULAN <?php $date = date('m'); if($date == '03'){ echo 'MARET '.date('Y');} ?></h4>
                        <div dir="ltr">
                            <div id="basic-bar" class="apex-charts" data-colors="#90ee7e"></div>
                        </div>
                    </div><!-- end card body-->
                </div><!-- end card -->
            </div><!-- end col-->
        </div><!-- end row-->
    </div>

<?php 
    include_once 'layouts/footer.php';
?>
<script>
var colors = ['#90ee7e'],
  dataColors = $('#basic-bar').data('colors')
dataColors && (colors = dataColors.split(','))
var options = {
    chart: { height: 380, type: 'bar', toolbar: { show: !1 } },
    plotOptions: { bar: { horizontal: !0 } },
    dataLabels: { enabled: !1 },
    series: [{ data: [25, 20, 18, 15, 15, 13, 10, 8, 8, 5] }],
    colors: colors,
    xaxis: {
      categories: [
        'Label Room',
        'Purchasing',
        'Business',
        'Development',
        'Payroll',
        'Accounting',
        'GMO',
        'HRM',
        'IT',
        'PPIC',
      ],
    },
    states: { hover: { filter: 'none' } },
    grid: { borderColor: '#f1f3fa' },
  },
  chart = new ApexCharts(document.querySelector('#basic-bar'), options)
chart.render()
</script>
