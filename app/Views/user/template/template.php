<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $profil[0]['nama_perusahaan']; ?></title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
   <?php
    $logo = base_url('assets/img/' .$profil[0]['logo']); 
   ?>
  <link href="<?= $logo?>" rel="icon">
  <link href="<?= $logo?>" rel="apple-touch-icon">

  <style>
        /*--------------------------------------------------------------
        # Peta
        --------------------------------------------------------------*/
        /* Peta full screen dengan responsivitas */
        #map {
            flex-grow: 1; /* Agar peta mengambil semua ruang yang tersedia */
            width: 100%;
            height: 100vh; /* Fullscreen di desktop */
            position: relative;
            z-index: 0;
        }

        /* Responsivitas untuk layar kecil */
        @media (max-width: 768px) {
            #map {
                height: 90vh; /* Kurangi tinggi pada layar kecil */
            }
        }

        @media (max-width: 480px) {
            #map {
                height: 80vh; /* Kurangi lebih jauh di layar lebih kecil */
            }
        }

        #content-wrapper {
            overflow: visible; /* Memastikan dropdown tidak terpotong */
        }
    </style>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Peta -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

  <!-- Vendor CSS Files -->
  <link href="<?= base_url('assets')?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url('assets')?>/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url('assets')?>/vendor/aos/aos.css" rel="stylesheet">
  <link href="<?= base_url('assets')?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="<?= base_url('assets')?>/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?= base_url('assets')?>/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="<?= base_url('assets')?>/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Medicio
  * Template URL: https://bootstrapmade.com/medicio-free-bootstrap-theme/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<body class="index-page">

    <?= $this->include('user/layout/header'); ?>

    <?= $this->renderSection('content'); ?>

    <?= $this->include('user/layout/footer'); ?>
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="<?= base_url('assets')?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url('assets')?>/vendor/php-email-form/validate.js"></script>
  <script src="<?= base_url('assets')?>/vendor/aos/aos.js"></script>
  <script src="<?= base_url('assets')?>/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="<?= base_url('assets')?>/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="<?= base_url('assets')?>/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="<?= base_url('assets')?>/js/main.js"></script>

</body>

</html>