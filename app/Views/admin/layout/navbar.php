<style>
   .nav-item .collapse {
        position: relative;
        z-index: 1050;
    }
    .company-name {
        font-size: 12px;
    }
</style>

<?php 
$userRole = session()->get('role'); // Ambil role dari session
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('admin/dashboard'); ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <img src="<?= base_url('assets/img/' . (!empty($profil) ? ($profil[0]['logo'] ?? 'default-logo.png') : 'default-logo.png')); ?>" 
                 alt="Logo Perusahaan" width="50">
        </div>
        <div class="sidebar-brand-text mx-3 company-name">
            <?= esc($profil[0]['nama_perusahaan'] ?? 'Nama Perusahaan Tidak Ditemukan'); ?>
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <?php if ($userRole == 'sekretaris'): ?>
        <!-- Sekretaris: Bisa akses semua menu -->
        <li class="nav-item <?= (uri_string() == 'admin/dashboard') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/dashboard'); ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Beranda</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Menu</div>

        <li class="nav-item <?= (uri_string() == 'admin/peta') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/peta'); ?>">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Peta</span>
            </a>
        </li>

        <li class="nav-item" <?= (uri_string() == 'admin/searchPeta') ? 'active' : ''; ?>>
            <a class="nav-link" href="<?= base_url('admin/searchPeta')?>">
                <i class="bi bi-compass-fill"></i>
                <span>Cari Peta</span>
            </a>
        </li>

        <?php
            $kelolaActive = in_array(uri_string(), [
                'admin/dataPengguna',
                'admin/dataPeta',
                'admin/dataSlider',
                'admin/dataPerusahaan'
            ]) ? 'active' : '';

            $kelolaShow = !empty($kelolaActive) ? 'hide' : ''; 
        ?>

        <li class="nav-item <?= $kelolaActive; ?>">
            <a class="nav-link <?= !empty($kelolaActive) ? '' : 'collapsed'; ?>" href="#" data-toggle="collapse" data-target="#collapseKelola"
                aria-expanded="<?= !empty($kelolaActive) ? 'true' : 'false'; ?>" aria-controls="collapseKelola">
                <i class="fas fa-fw fa-cog"></i>
                <span>Kelola</span>
            </a>
            <div id="collapseKelola" class="collapse <?= $kelolaShow; ?>" aria-labelledby="headingTwo" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Kelola:</h6>
                    <a class="collapse-item <?= (uri_string() == 'admin/dataPengguna') ? 'active' : ''; ?>" href="<?= base_url('admin/dataPengguna'); ?>">Pengguna</a>
                    <a class="collapse-item <?= (uri_string() == 'admin/dataPeta') ? 'active' : ''; ?>" href="<?= base_url('admin/dataPeta'); ?>">Peta</a>
                    <a class="collapse-item <?= (uri_string() == 'admin/dataSlider') ? 'active' : ''; ?>" href="<?= base_url('admin/dataSlider'); ?>">Slider</a>
                    <a class="collapse-item <?= (uri_string() == 'admin/dataPerusahaan') ? 'active' : ''; ?>" href="<?= base_url('admin/dataPerusahaan'); ?>">Profil Perusahaan</a>
                    <a class="collapse-item <?= (uri_string() == 'admin/dataPenggunaanTanah') ? 'active' : ''; ?>" href="<?= base_url('admin/dataPenggunaanTanah'); ?>">Penggunaan Tanah</a>
                </div>
            </div>
        </li>

        <?php
            $riwayatActive = in_array(uri_string(), [
                'admin/riwayatHarga',
                'admin/riwayatHargaKelurahan'
            ]) ? 'active' : '';

            $riwayatShow = !empty($riwayatActive) ? 'hide' : ''; 
        ?>

        <li class="nav-item <?= $riwayatActive; ?>">
            <a class="nav-link <?= !empty($riwayatActive) ? '' : 'collapsed'; ?>" href="#" data-toggle="collapse" data-target="#collapseRiwayat"
                aria-expanded="<?= !empty($riwayatActive) ? 'true' : 'false'; ?>" aria-controls="collapseRiwayat">
                <i class="bi bi-file-earmark-bar-graph-fill"></i>
                <span>Riwayat Harga</span>
            </a>
            <div id="collapseRiwayat" class="collapse <?= $riwayatShow; ?>" aria-labelledby="headingTwo" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Riwayat Harga:</h6>
                    <a class="collapse-item <?= (uri_string() == 'admin/riwayatHarga') ? 'active' : ''; ?>" href="<?= base_url('admin/riwayatHarga'); ?>">Keseluruhan</a>
                    <a class="collapse-item <?= (uri_string() == 'admin/riwayatHargaKelurahan') ? 'active' : ''; ?>" href="<?= base_url('admin/riwayatHargaKelurahan'); ?>">Kelurahan</a>
                </div>
            </div>
        </li>


    <?php elseif ($userRole == 'pegawai'): ?>
        <!-- Pegawai: Hanya bisa akses menu Peta -->
         <li class="nav-item <?= (uri_string() == 'admin/dashboard') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/dashboard'); ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Beranda</span>
            </a>
        </li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Menu</div>


        <li class="nav-item" <?= (uri_string() == 'admin/searchPeta') ? 'active' : ''; ?>>
            <a class="nav-link" href="<?= base_url('admin/searchPeta')?>">
                <i class="bi bi-compass-fill"></i>
                <span>Peta</span>
            </a>
        </li>

        <?php
            $riwayatActive = in_array(uri_string(), [
                'admin/riwayatHarga',
                'admin/riwayatHargaKelurahan'
            ]) ? 'active' : '';

            $riwayatShow = !empty($riwayatActive) ? 'hide' : ''; 
        ?>

        <li class="nav-item <?= $riwayatActive; ?>">
            <a class="nav-link <?= !empty($riwayatActive) ? '' : 'collapsed'; ?>" href="#" data-toggle="collapse" data-target="#collapseRiwayat"
                aria-expanded="<?= !empty($riwayatActive) ? 'true' : 'false'; ?>" aria-controls="collapseRiwayat">
                <i class="bi bi-file-earmark-bar-graph-fill"></i>
                <span>Riwayat Harga</span>
            </a>
            <div id="collapseRiwayat" class="collapse <?= $riwayatShow; ?>" aria-labelledby="headingTwo" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Riwayat Harga:</h6>
                    <a class="collapse-item <?= (uri_string() == 'admin/riwayatHarga') ? 'active' : ''; ?>" href="<?= base_url('admin/riwayatHarga'); ?>">Keseluruhan</a>
                    <a class="collapse-item <?= (uri_string() == 'admin/riwayatHargaKelurahan') ? 'active' : ''; ?>" href="<?= base_url('admin/riwayatHargaKelurahan'); ?>">Kelurahan</a>
                </div>
            </div>
        </li>

    <?php endif; ?>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>


<!-- End of Sidebar -->