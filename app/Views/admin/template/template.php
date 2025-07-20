<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

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

    <title><?= ucfirst($routeName); ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets-admin')?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chroma-js/2.1.0/chroma.min.js"></script>

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets-admin')?>/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?= $this->include('admin/layout/navbar'); ?>
        
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?= $this->include('admin/layout/header'); ?>

                <?= $this->renderSection('content'); ?>

            </div>
            <!-- End of Main Content -->

            <?= $this->include('admin/layout/footer'); ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Apakah kamu yakin untuk keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Tekan "Logout" jika yakin untuk keluar dari aplikasi ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="<?= base_url('logout') ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets-admin')?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets-admin')?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets-admin')?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets-admin')?>/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= base_url('assets-admin')?>/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= base_url('assets-admin')?>/js/demo/chart-area-demo.js"></script>
    <script src="<?= base_url('assets-admin')?>/js/demo/chart-pie-demo.js"></script>

    <!-- Page level plugins -->
    <script src="<?= base_url('assets-admin')?>/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('assets-admin')?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= base_url('assets-admin')?>/js/demo/datatables-demo.js"></script>

    <script>

        $(document).ready(function() {
            $('.dropdown-toggle').dropdown();
        });

        let timeout;

        // Fungsi untuk mengatur timeout session
        function startSessionTimer() {
            // Waktu dalam milidetik (30 menit)
            let timeoutDuration = 30 * 60 * 1000;

            // Reset timer setiap kali ada interaksi (klik, gerakkan mouse, ketik)
            function resetTimer() {
                clearTimeout(timeout);
                timeout = setTimeout(logout, timeoutDuration);  // Logout setelah waktu habis
            }

            // Fungsi logout otomatis
            function logout() {
                alert("Sesi Anda telah berakhir. Anda akan logout secara otomatis.");
                window.location.href = "<?php echo base_url('logout'); ?>";  // Redirect ke halaman logout
            }

            // Event listeners untuk interaksi pengguna
            document.addEventListener('mousemove', resetTimer);
            document.addEventListener('keydown', resetTimer);
            document.addEventListener('click', resetTimer);

            // Mulai timer
            resetTimer();
        }

        // Panggil fungsi untuk memulai timer saat halaman dimuat
        window.onload = startSessionTimer;
    </script>

</body>

</html>