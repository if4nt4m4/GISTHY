<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<style>
    /* Improved responsive styles */
    @media (max-width: 1200px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .card-body {
            padding: 1rem;
        }
    }
    
    @media (max-width: 992px) {
        .action-buttons .btn {
            margin-bottom: 5px;
            display: block;
            width: 100%;
        }
        
        .btn-group {
            display: flex;
            flex-direction: column;
        }
        
        .btn-group .dropdown-menu {
            position: static !important;
            transform: none !important;
        }
    }
    
    @media (max-width: 768px) {
        .date-filter-container {
            flex-direction: column;
            width: 100%;
        }
        
        .date-filter-group {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .toolbar-container {
            flex-direction: column;
        }
        
        .toolbar-buttons {
            margin-top: 0.5rem;
            justify-content: flex-start !important;
        }
        
        .table th, .table td {
            padding: 0.5rem;
            font-size: 0.875rem;
        }
    }
    
    @media (max-width: 576px) {
        .btn-icon-split .text {
            display: none;
        }
        
        .btn-icon-split .icon {
            margin-right: 0;
        }
        
        .modal-dialog {
            margin: 0.5rem auto;
        }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .action-buttons a, .action-buttons button {
            margin: 2px 0;
        }
    }
    
    /* Enhanced UI elements */
    .btn-export-all {
        background-color: #28a745;
        border-color: #28a745;
        margin-right: 0.5rem;
    }
    
    .btn-export-all:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }
    
    .date-filter-group {
        min-width: 180px;
    }
    
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }
    
    .badge-secondary:hover {
        background-color: #5a6268;
    }
    
    /* Tooltip enhancements */
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 6px 0;
        border-radius: 15px;
        text-align: center;
        font-size: 12px;
        line-height: 1.42857;
        margin: 2px;
    }
    
    /* Modal improvements */
    .modal-body {
        padding: 1.5rem;
    }
    
    /* Table improvements */
    .table th {
        white-space: nowrap;
        position: relative;
    }
    
    .table th:hover::after {
        content: attr(data-title);
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: #fff;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 100;
    }
    
    /* Responsive dropdown */
    .dropdown-menu {
        min-width: 100%;
    }
    
    @media (min-width: 768px) {
        .dropdown-menu {
            min-width: 200px;
        }
    }
    
    /* Animation enhancements */
    .btn-icon-split {
        transition: all 0.2s ease;
    }
    
    .btn-icon-split:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<div class="container-fluid fade-in">
    <h1 class="h3 mb-3 text-gray-800">Data Peta</h1>
    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data untuk Keperluan Peta</h6>
            <div class="mt-2 mt-md-0">
                <button id="btnImport" class="btn btn-primary btn-icon-split" data-toggle="tooltip" title="Import data dari Excel">
                    <span class="icon text-white-50"><i class="fas fa-file-import"></i></span>
                    <span class="text">Import Data</span>
                </button>
            </div>
        </div>
        
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between mb-4 toolbar-container">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center date-filter-container mb-3 mb-md-0">
                    <form id="exportFilterForm" class="d-flex flex-column flex-md-row w-100">
                        <div class="form-group mr-md-2 mb-2 mb-md-0 date-filter-group">
                            <label for="startDate" class="sr-only">Dari Tanggal</label>
                            <select class="form-control form-control-sm date-filter" id="startDate" name="startDate" data-toggle="tooltip" title="Pilih bulan awal untuk filter data">
                                <option value="">Pilih Bulan Awal</option>
                                <?php 
                                $years = array_unique(array_map(function($date) {
                                    return date('Y', strtotime($date));
                                }, $availableDates));
                                
                                foreach ($years as $year): 
                                    $months = array_filter($availableDates, function($date) use ($year) {
                                        return date('Y', strtotime($date)) == $year;
                                    });
                                    $months = array_unique(array_map(function($date) {
                                        return date('m', strtotime($date));
                                    }, $months));
                                    sort($months);
                                ?>
                                    <optgroup label="<?= $year ?>">
                                        <?php foreach ($months as $month): ?>
                                            <option value="<?= $year.'-'.$month.'-01' ?>">
                                                <?= date('F Y', strtotime($year.'-'.$month.'-01')) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group date-filter-group">
                            <label for="endDate" class="sr-only">Sampai Tanggal</label>
                            <select class="form-control form-control-sm date-filter" id="endDate" name="endDate" data-toggle="tooltip" title="Pilih bulan akhir untuk filter data">
                                <option value="">Pilih Bulan Akhir</option>
                                <?php foreach ($years as $year): 
                                    $months = array_filter($availableDates, function($date) use ($year) {
                                        return date('Y', strtotime($date)) == $year;
                                    });
                                    $months = array_unique(array_map(function($date) {
                                        return date('m', strtotime($date));
                                    }, $months));
                                    sort($months);
                                ?>
                                    <optgroup label="<?= $year ?>">
                                        <?php foreach ($months as $month): ?>
                                            <option value="<?= $year.'-'.$month.'-'.date('t', strtotime($year.'-'.$month.'-01')) ?>">
                                                <?= date('F Y', strtotime($year.'-'.$month.'-01')) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center toolbar-buttons">
                    
                    <div class="btn-group mb-2 mb-sm-0 mr-2">
                        <button id="btnExportExcel" class="btn btn-success btn-icon-split" data-toggle="tooltip" title="Export data dengan filter">
                            <span class="icon text-white-50"><i class="fas fa-file-excel"></i></span>
                            <span class="text d-none d-sm-inline">Export</span>
                        </button>
                        <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item export-option" href="#" data-format="excel">
                                <i class="fas fa-file-excel text-success mr-2"></i>Excel (Data Terfilter)
                            </a>
                            <a class="dropdown-item export-option" href="#" data-format="pdf">
                                <i class="fas fa-file-pdf text-danger mr-2"></i>PDF (Data Terfilter)
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item export-all-option" href="#" data-format="excel-all">
                                <i class="fas fa-file-excel text-success mr-2"></i>Excel (Semua Data)
                            </a>
                            <a class="dropdown-item export-all-option" href="#" data-format="pdf-all">
                                <i class="fas fa-file-pdf text-danger mr-2"></i>PDF (Semua Data)
                            </a>
                        </div>
                    </div>
                    
                    <a href="<?= base_url('admin/tambahDataPeta') ?>" class="btn btn-info btn-icon-split" data-toggle="tooltip" title="Tambah data baru">
                        <span class="icon text-white-50"><i class="bi bi-plus-circle-fill"></i></span>
                        <span class="text d-none d-sm-inline">Tambah Data</span>
                    </a>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th data-title="Nomor urut">No</th>
                            <th data-title="Jenis penggunaan tanah">Penggunaan Tanah</th>
                            <th data-title="Jenis indikasi harga">Indikasi</th>
                            <th data-title="Nama desa/kelurahan">Desa/Kelurahan</th>
                            <th data-title="Nama kecamatan">Kecamatan</th>
                            <th data-title="Nama kabupaten/kota">Kabupaten/Kota</th>
                            <th data-title="Tanggal update terakhir">Tgl/Th Baru</th>
                            <th data-title="Harga terkini per meter">Harga Terkini</th>
                            <th data-title="Luas tanah dalam meter">Luas Tanah</th>
                            <th data-title="Luas bangunan dalam meter">Luas Bangunan</th>
                            <th data-title="Nama pemilik atau agen">Nama Pemilik/Agen</th>
                            <th data-title="Nomor telepon kontak">No Telepon</th>                             
                            <th data-title="Riwayat perubahan harga">Riwayat Harga</th>
                            <th data-title="Koordinat geografis">Koordinat</th>
                            <th data-title="Aksi yang tersedia">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($tanah as $data): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['kawasan'] ?></td>                                
                                <td class="text-capitalize"><?= $data['indikasi']?></td>
                                <td><?= $data['kelurahan'] ?></td>
                                <td><?= $data['kecamatan'] ?></td>
                                <td><?= $data['wilayah'] ?></td>
                                <td><?= $data['waktu_terkini'] ?></td>                                                                
                                <td><?= number_format($data['harga_terkini'], 2, ',', '.') ?></td>                                
                                <td><?= $data['luas_tanah'] ?></td>
                                <td><?= $data['luas_bangunan'] ?></td>
                                <td><?= $data['nama_pemilik'] ?></td>
                                <td><?= $data['no_telp'] ?></td>
                                <td>                                
                                    <?php if (!empty($data['waktu'] && $data['harga']) && isset($data['harga'], $data['waktu']) && $data['harga'] != 0.00 && $data['waktu'] != '0000-00-00'): ?>
                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalRiwayat<?= $data['id_tanah'] ?>" title="Lihat riwayat perubahan harga">
                                            <i class="bi bi-clock-history"></i> <span class="d-none d-md-inline">Riwayat</span>
                                        </button>
                                    <?php elseif (isset($data['harga'], $data['waktu']) && $data['harga'] == 0.00 && $data['waktu'] == '0000-00-00'): ?>
                                        <span class="badge badge-secondary">Belum Ada Harga</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Tidak Ada Riwayat</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($data['coordinates']) && count($data['coordinates']) >= 3): ?>
                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalPeta<?= $data['id_tanah'] ?>" title="Lihat peta lokasi">
                                            <i class="fas fa-map"></i> <span class="d-none d-md-inline">Peta</span>
                                        </button>
                                    <?php else: ?>
                                        <form action="<?= base_url('admin/generatePolygon/'.$data['id_tanah']) ?>" method="post">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-success btn-sm" title="Buat polygon otomatis">
                                                <i class="bi bi-magic"></i> <span class="d-none d-md-inline">Buat Polygon</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-warning btn-circle edit-harga" title="Edit Harga Tanah" 
                                                data-id_tanah="<?= $data['id_tanah'] ?>" 
                                                data-harga="<?= $data['harga_terkini'] ?>" 
                                                data-waktu="<?= $data['waktu_terkini'] ?>">
                                            <i class="bi bi-cash"></i>
                                        </button>
                                        
                                        <a href="<?= base_url('admin/editDataPeta/'.$data['id_tanah']) ?>" class="btn btn-primary btn-circle" title="Edit Data Peta">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        
                                        <a href="<?= base_url('admin/hapusDataPeta/'.$data['id_tanah']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-circle" title="Hapus Data Peta">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan peta -->
<?php foreach ($tanah as $data): if (!empty($data['coordinates']) && count($data['coordinates']) >= 3): ?>
    <div class="modal fade" id="modalPeta<?= $data['id_tanah'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalPetaLabel<?= $data['id_tanah'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Peta Kawasan <?= $data['kawasan'] ?></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div id="peta<?= $data['id_tanah'] ?>" style="height: 500px;"></div>
                    <div class="p-3">
                        <p class="mb-1"><strong>Legenda:</strong></p>
                        <p class="mb-1"><span style="color: #3388ff;">■</span> Batas Tanah (Polygon)</p>
                        <p class="mb-1"><span style="color: #ff0000;">■</span> Titik Referensi</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-warning" 
                            onclick="window.location.href='<?= base_url('admin/editKoordinat/' . $data['id_tanah']) ?>'">
                        <i class="bi bi-pencil-fill"></i> Edit Koordinat
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; endforeach; ?>

<!-- Modal untuk mengubah harga -->
<div class="modal fade" id="modalEditHarga" tabindex="-1" role="dialog" aria-labelledby="modalEditHargaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalEditHargaLabel">Edit Harga Tanah</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditHarga" method="post" action="<?= base_url('admin/updateHarga') ?>">
                <div class="modal-body">
                    <input type="hidden" name="id_tanah" id="id_tanah">
                    <div class="form-group">
                        <label for="waktu_terkini">Tanggal Update</label>
                        <input type="date" class="form-control" name="waktu_terkini" id="waktu_terkini" required>
                        <small class="form-text text-muted">Masukkan tanggal update harga terbaru</small>
                    </div>
                    <div class="form-group">
                        <label for="harga_terkini">Harga Terkini (per m²)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control" name="harga_terkini" id="harga_terkini" required>
                            <div class="input-group-append">
                                <span class="input-group-text">/m²</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Masukkan harga tanah per meter persegi</small>
                    </div>                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan riwayat harga -->
<?php foreach ($tanah as $data): ?>
    <div class="modal fade" id="modalRiwayat<?= $data['id_tanah'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalRiwayatLabel<?= $data['id_tanah'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalRiwayatLabel<?= $data['id_tanah'] ?>">Riwayat Harga untuk <?= $data['kawasan'] ?></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Harga (Rp/m²)</th>
                                    <th>Perubahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $prevHarga = null;
                                if (!empty($data['riwayat_harga'])): 
                                    foreach ($data['riwayat_harga'] as $riwayat): 
                                        $change = $prevHarga !== null ? $riwayat['harga'] - $prevHarga : 0;
                                        $changeClass = $change > 0 ? 'text-danger' : ($change < 0 ? 'text-success' : 'text-muted');
                                        $changeIcon = $change > 0 ? '↑' : ($change < 0 ? '↓' : '→');
                                        $prevHarga = $riwayat['harga'];
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d-m-Y', strtotime($riwayat['waktu'])) ?></td>
                                        <td><?= number_format($riwayat['harga'], 2, ',', '.') ?></td>
                                        <td class="<?= $changeClass ?>">
                                            <?= $changeIcon ?> <?= $change != 0 ? number_format(abs($change), 2, ',', '.') : '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; 
                                else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada riwayat harga yang tersedia.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Modal untuk Import -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importModalLabel">Import Data Peta</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/importPeta') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file_excel">Pilih File Excel</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file_excel" name="file_excel" accept=".xlsx,.xls,.csv" required>
                            <label class="custom-file-label" for="file_excel">Pilih file...</label>
                        </div>
                        <small class="form-text text-muted">
                            Format file harus .xlsx, .xls, atau .csv. Maksimal 2MB.
                            <a href="<?= base_url('admin/exportPeta') ?>" class="d-block mt-2">
                                <i class="fas fa-download mr-1"></i> Download template
                            </a>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip({
            placement: 'top',
            trigger: 'hover'
        });
        
        // Update file input label
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
        
        // Handle export options
        $('#btnExportExcel').click(function() {
            exportData('excel');
        });
        
        $('.export-option').click(function(e) {
            e.preventDefault();
            var format = $(this).data('format');
            exportData(format);
        });
        
        $('.export-all-option').click(function(e) {
            e.preventDefault();
            var format = $(this).data('format').replace('-all', '');
            if (confirm('Export semua data termasuk riwayat harga? Proses ini mungkin memakan waktu lebih lama.')) {
                var url = '<?= base_url('admin/exportPeta') ?>?exportAll=true&format=' + format;
                window.location.href = url;
            }
        });
        
        function exportData(format) {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            
            // Validate date selection
            if ((startDate && !endDate) || (!startDate && endDate)) {
                alert('Mohon pilih kedua tanggal filter atau biarkan keduanya kosong');
                return;
            }
            
            var url = '<?= base_url('admin/exportPeta') ?>?format=' + format;
            
            if (startDate && endDate) {
                url += '&startDate=' + startDate + '&endDate=' + endDate;
            }
            
            window.location.href = url;
        }

        // Fungsi untuk mengurutkan titik-titik koordinat searah jarum jam
        function sortCoordinatesClockwise(points) {
            // Cari titik pusat (centroid)
            const centroid = points.reduce((acc, point) => {
                return [acc[0] + point[0]/points.length, acc[1] + point[1]/points.length];
            }, [0, 0]);
            
            // Urutkan berdasarkan sudut dari centroid
            return points.sort((a, b) => {
                const angleA = Math.atan2(a[0] - centroid[0], a[1] - centroid[1]);
                const angleB = Math.atan2(b[0] - centroid[0], b[1] - centroid[1]);
                return angleA - angleB;
            });
        }

        <?php foreach ($tanah as $data): if (!empty($data['coordinates'])): ?>
            $('#modalPeta<?= $data['id_tanah'] ?>').on('shown.bs.modal', function () {
                // Inisialisasi peta dengan titik referensi sebagai pusat
                var map = L.map('peta<?= $data['id_tanah'] ?>').setView(
                    [<?= $data['titik_referensi']['titik_latitude'] ?>, <?= $data['titik_referensi']['titik_longitude'] ?>], 
                    15
                );
                
                // base layer OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                
                // Proses koordinat polygon
                var rawCoordinates = [
                    <?php foreach ($data['coordinates'] as $coord): ?>
                        [<?= $coord['latitude'] ?>, <?= $coord['longitude'] ?>],
                    <?php endforeach; ?>
                ];
                
                // Urutkan koordinat searah jarum jam
                var sortedCoordinates = sortCoordinatesClockwise(rawCoordinates);
                
                // Buat polygon batas tanah (warna biru) dengan koordinat yang sudah diurutkan
                var tanahPolygon = L.polygon(sortedCoordinates, {
                    color: '#3388ff',
                    weight: 2,
                    fillColor: '#3388ff',
                    fillOpacity: 0.3
                }).addTo(map);
                
                // Tambahkan marker titik referensi dengan icon default Leaflet
                var marker = L.marker(
                    [<?= $data['titik_referensi']['titik_latitude'] ?>, <?= $data['titik_referensi']['titik_longitude'] ?>],
                    {icon: L.divIcon({
                        className: 'custom-marker',
                        html: '<i class="fas fa-map-marker-alt" style="color: #ff0000; font-size: 24px;"></i>',
                        iconSize: [24, 24],
                        iconAnchor: [12, 24]
                    })}
                ).addTo(map);
                
                // Bind popup informasi untuk marker
                marker.bindPopup(
                    `<b>Titik Referensi</b><br>
                    Lokasi: <?= $data['kawasan'] ?><br>
                    Koordinat: <?= $data['titik_referensi']['titik_latitude'] ?>, <?= $data['titik_referensi']['titik_longitude'] ?>`
                ).openPopup();
                
                // Bind popup untuk polygon
                tanahPolygon.bindPopup(
                    `<b>Batas Tanah</b><br>
                    <?= $data['kawasan'] ?><br>
                    <?= count($data['coordinates']) ?> titik koordinat`
                );
                
                // Fit bounds agar semua elemen terlihat
                var bounds = L.latLngBounds(sortedCoordinates).extend(marker.getLatLng());
                map.fitBounds(bounds);
                
                // Handle resize peta
                setTimeout(function() {
                    map.invalidateSize();
                }, 200);
            });
        <?php endif; endforeach; ?>

        $('.edit-harga').on('click', function() {
            var idTanah = $(this).data('id_tanah');
            var hargaTerkini = $(this).data('harga'); // Pastikan ini angka murni
            var waktuTerkini = $(this).data('waktu');

            $('#id_tanah').val(idTanah);
            $('#harga_terkini').val(hargaTerkini); // Langsung isi angka, tanpa formatting
            $('#waktu_terkini').val(waktuTerkini);

            $('#modalEditHarga').modal('show');
        });

        // Format tampilan saat input kehilangan fokus (opsional)
        $('#harga_terkini').on('blur', function() {
            let value = parseFloat($(this).val().replace(/[^\d,]/g, '').replace(',', '.'));
            if (!isNaN(value)) {
                $(this).val(value.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }
        });

        // Kembalikan ke angka murni saat input aktif/focus
        $('#harga_terkini').on('focus', function() {
            let value = parseFloat($(this).val().replace(/[^\d,]/g, '').replace(',', '.'));
            if (!isNaN(value)) {
                $(this).val(value);
            }
        });

        // Pastikan mengirim angka murni saat submit
        $('#formEditHarga').on('submit', function(e) {
            // Hapus formatting ribuan sebelum submit
            let harga = $('#harga_terkini').val().replace(/\./g, '').replace(',', '.');
            $('#harga_terkini').val(harga);
            return true;
        });

        // Tampilkan modal import saat tombol diklik
        $('#btnImport').click(function() {
            $('#importModal').modal('show');
        });
    });
</script>

<style>
    .custom-marker {
        background: none;
        border: none;
    }
    
    .custom-marker i {
        text-shadow: 0 0 3px white;
    }
</style>

<?= $this->endSection(); ?>