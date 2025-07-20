<?= $this->extend('admin/template/template'); ?>
<?= $this->Section('content'); ?>

<?php 
$colors = [
    ['border' => 'border-left-primary', 'icon' => 'text-primary'],
    ['border' => 'border-left-success', 'icon' => 'text-success'],
    ['border' => 'border-left-info', 'icon' => 'text-info'],
    ['border' => 'border-left-warning', 'icon' => 'text-warning'],
    ['border' => 'border-left-danger', 'icon' => 'text-danger'],
];
$index = 0;
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Beranda</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <h6 class="h6 mb-4 text-primary px-2">Informasi Mengenai Website</h6>
        </div>
        <!-- Jumlah Data Tanah -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Data Tanah</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jumlahTanah ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard-data-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Pengguna</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jumlahUsers?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h6 class="h6 mb-4 text-primary px-2">Rata-Rata Harga di Masing-Masing Wilayah</h6>
        </div>
        <!-- Rata-rata Harga di wilayah -->
        <?php if (!empty($rataRataHargaByWilayah)): ?>
            <?php foreach ($rataRataHargaByWilayah as $wilayah => $rataRata): ?>
                <?php 
                $color = $colors[$index % count($colors)]; // rotasi warna
                $index++;
                ?>
                <!-- Card untuk Setiap Wilayah -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card <?= $color['border'] ?> shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1 <?= $color['icon'] ?>">
                                        <?= esc($wilayah) ?>
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp. <?= number_format($rataRata, 0, ',', '.'); ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-graph-up fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Card Jika Tidak Ada Data -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Rata-Rata Harga di Wilayah
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Tidak ada data rata-rata harga.
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-graph-up fa-2x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-12">
            <h6 class="h6 mb-4 text-primary px-2">Harga Tertinggi Di sebuah Kelurahan/Desa</h6>
        </div>
        <!-- Harga Tertinggi di kelurahan/desa -->
        <?php if(!empty($hargaTertinggiKelurahan)): ?>
            <?php $index = 0; ?>
            <?php foreach ($hargaTertinggiKelurahan as $data): ?>
                <?php $color = $colors[$index % count($colors)]; $index++; ?>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card <?= $color['border'] ?> shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold <?= $color['icon'] ?> text-uppercase mb-1">
                                        Kelurahan <?= esc($data['kelurahan']) ?>
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp. <?= number_format($data['harga_tertinggi'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-house-fill fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-12">    
            <h6 class="h6 mb-4 text-primary px-2">Harga Tertingggi di Sebuah Kecamatan</h6>
        </div>
        <?php if(!empty($hargaTertinggiKecamatan)): ?>
            <?php $index = 0; ?>
            <?php foreach ($hargaTertinggiKecamatan as $data): ?>
                <?php $color = $colors[$index % count($colors)]; $index++; ?>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card <?= $color['border'] ?> shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold <?= $color['icon'] ?> text-uppercase mb-1">
                                        Kecamatan <?= esc($data['kecamatan']) ?>
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp.<?= number_format($data['harga_tertinggi'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-geo-fill fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-12">    
            <h6 class="h6 mb-4 text-primary px-2">Top 5 Harga Tertinggi di Kelurahan</h6>
        </div>
        <!-- Top 5 Harga Teringgi di Kelurahan -->
        <?php if(!empty($top5HargaTertinggi)): ?>
            <?php $index = 0; ?>
            <?php foreach ($top5HargaTertinggi as $data): ?>
                <?php $color = $colors[$index % count($colors)]; $index++; ?>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card <?= $color['border'] ?> shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold <?= $color['icon'] ?> text-uppercase mb-1">
                                        <?= esc($data['kelurahan']) ?> di Kecamatan <?= esc($data['kecamatan']) ?>                                    
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp. <?= number_format($data['harga_terkini'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-arrow-up-circle-fill fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-muted">Tidak ada data.</div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-12">    
            <h6 class="h6 mb-4 text-primary px-2">Top 5 Harga Terendah di Kelurahan</h6>
        </div>
        <!-- Top 5 Harga Terendah di Kelurahan -->
        <?php if(!empty($top5HargaTerendah)): ?>
            <?php $index = 0; ?>
            <?php foreach ($top5HargaTerendah as $data): ?>
                <?php $color = $colors[$index % count($colors)]; $index++; ?>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card <?= $color['border'] ?> shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold <?= $color['icon'] ?> text-uppercase mb-1">
                                        <?= esc($data['kelurahan']) ?> di Kecamatan <?= esc($data['kecamatan']) ?>
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp. <?= number_format($data['harga_terkini'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-arrow-down-circle-fill fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-muted">Tidak ada data.</div>
        <?php endif; ?>
    </div>
</div>
<!-- /.container-fluid -->
<?= $this->endSection('content') ?>