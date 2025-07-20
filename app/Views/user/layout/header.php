<header id="header" class="header sticky-top">
    <div class="branding d-flex align-items-center">
      <div class="container position-relative d-flex align-items-center justify-content-end">
        <a href="<?= base_url('/')?>" class="logo d-flex align-items-center me-auto">
          <img src="<?= base_url('assets/img/' . (!empty($profil) ? ($profil[0]['logo'] ?? 'default-logo.png') : 'default-logo.png')); ?>" 
          alt="Logo Perusahaan" width="100%">
        </a>
        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#hero" class="active">Beranda</a></li>
            <li><a href="#about">Tentang Kami</a></li>
            <li><a href="#statistics">Data Statistik</a></li>
            <li><a href="#map-section">Peta</a></li>
            <li><a href="#contact">Kontak</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
        <a class="cta-btn" href="https://kjppthy.com/">Kunjungi Website Utama</a>
      </div>
    </div>
</header>