<?php
include_once 'layouts/header.php';
require 'functions/function_fingerprint.php';

$data = getAllFingerprint();
$gedung = getAllGedung();

// Kita kelompokkan data berdasarkan gedung untuk navigasi tab/filter
$grouped_data = [];
foreach ($data as $d) {
    $g_name = $d['nama_gedung'] ?? $d['kode_gedung'];
    if (!isset($grouped_data[$g_name])) {
        $grouped_data[$g_name] = [];
    }
    $grouped_data[$g_name][] = $d;
}
?>

<div class="container-fluid">
    <div class="page-title-box d-flex align-items-center justify-content-between py-3">
        <h4 class="page-title mb-0">
            <i class="mdi mdi-map-marker-radius text-primary me-2"></i>Visual Mapping 3D Fingerprint
        </h4>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Daftar Gedung</div>
                <div class="list-group list-group-flush">
                    <?php $first = true; foreach ($grouped_data as $nama_gedung => $list): ?>
                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center gedung-selector" onclick="showGedung('<?= $nama_gedung ?>')">
                            <?= $nama_gedung ?>
                            <span class="badge bg-primary rounded-pill"><?= count($list) ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="card mt-3 shadow-sm border-0">
                <div class="card-body">
                    <h6>Legend:</h6>
                    <div class="d-flex align-items-center mb-2">
                        <div class="dot bg-success me-2"></div> <small>Aktif (IN)</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="dot bg-info me-2"></div> <small>Aktif (OUT)</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dot bg-danger me-2"></div> <small>Rusak / Off</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="map-container shadow-sm border">
                <div class="map-scene" id="mapScene">
                    <div class="floor-plate">
                        <div class="floor-label" id="activeGedungName">Pilih Gedung di Samping</div>
                        
                        <div id="fingerprintPoints">
                            </div>
                    </div>
                </div>
                
                <div class="map-controls">
                    <button class="btn btn-sm btn-light border" onclick="rotateMap(-45)"><i class="mdi mdi-rotate-left"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="rotateMap(45)"><i class="mdi mdi-rotate-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Container Utama Map */
    .map-container {
        background: #e9ecef;
        height: 600px;
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        perspective: 1000px; /* Memberi efek kedalaman 3D */
    }

    .map-scene {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.5s ease;
    }

    /* Pelat Lantai 3D */
    .floor-plate {
        width: 600px;
        height: 400px;
        background: #ffffff;
        background-image: radial-gradient(#dee2e6 1px, transparent 1px);
        background-size: 20px 20px;
        position: relative;
        transform: rotateX(60deg) rotateZ(-30deg); /* Kunci tampilan 3D Isometric */
        transform-style: preserve-3d;
        box-shadow: -20px 20px 50px rgba(0,0,0,0.2);
        transition: all 0.5s ease;
        border: 5px solid #fff;
    }

    .floor-label {
        position: absolute;
        top: 10px;
        left: 10px;
        transform: rotateZ(0deg);
        font-weight: bold;
        color: #adb5bd;
        font-size: 20px;
    }

    /* Pin / Marker Fingerprint */
    .fp-marker {
        position: absolute;
        width: 12px;
        height: 12px;
        background: #000;
        border-radius: 50%;
        transform-style: preserve-3d;
        cursor: pointer;
        transition: 0.3s;
    }

    /* Efek Berdiri (Lollipop) agar marker tegak lurus di lantai miring */
    .fp-marker::after {
        content: '';
        position: absolute;
        bottom: 50%;
        left: 50%;
        width: 2px;
        height: 30px;
        background: inherit;
        transform-origin: bottom;
        transform: rotateX(-90deg); /* Membuat tiang berdiri tegak */
    }

    .fp-marker .fp-label {
        position: absolute;
        bottom: 40px;
        left: -40px;
        width: 100px;
        background: rgba(255,255,255,0.9);
        padding: 5px;
        border-radius: 5px;
        font-size: 10px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transform: rotateX(-90deg) rotateY(30deg); /* Label menghadap user */
        pointer-events: none;
        display: none;
    }

    .fp-marker:hover .fp-label {
        display: block;
    }

    .dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; }
    .map-controls { position: absolute; bottom: 20px; right: 20px; z-index: 10; }
</style>

<script>
    const allData = <?= json_encode($grouped_data) ?>;
    let currentRotation = -30;

    function showGedung(namaGedung) {
        document.getElementById('activeGedungName').innerText = namaGedung;
        const container = document.getElementById('fingerprintPoints');
        container.innerHTML = ''; 

        const devices = allData[namaGedung];
        
        devices.forEach((d, index) => {
            const posX = (index * 80) % 550 + 20; 
            const posY = (index * 60) % 350 + 20;

            let color = '#28a745'; // Aktif IN
            if(d.fungsi === 'OUT') color = '#17a2b8'; // Aktif OUT
            if(d.status !== 'aktif') color = '#dc3545'; // Rusak/Nonaktif

            const marker = document.createElement('div');
            marker.className = 'fp-marker';
            marker.style.left = posX + 'px';
            marker.style.top = posY + 'px';
            marker.style.backgroundColor = color;
            marker.style.boxShadow = `0 0 10px ${color}`;

            marker.innerHTML = `
                <div class="fp-label">
                    <strong>${d.nama_fingerprint}</strong><br>
                    IP: ${d.ip_address}<br>
                    Status: ${d.status}
                </div>
            `;

            container.appendChild(marker);
        });
    }

    function rotateMap(deg) {
        currentRotation += deg;
        const floor = document.querySelector('.floor-plate');
        floor.style.transform = `rotateX(60deg) rotateZ(${currentRotation}deg)`;
    }
</script>

<?php include_once 'layouts/footer.php'; ?>