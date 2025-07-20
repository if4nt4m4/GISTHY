<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Profil Perusahaan</h1>
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
<!-- Tombol Tambah Slider -->
<div class="d-flex justify-content-end mb-3">
    <!-- <button class="btn btn-success btn-icon-split" data-toggle="modal" data-target="#tambahProfilModal">
        <span class="icon text-white-50"><i class="bi bi-plus-circle-fill"></i></span>
        <span class="text">Tambah Data</span>
    </button> -->
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Perusahaan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Perusahaan</th>
                        <th>Deskripsi</th>
                        <th>Logo</th>
                        <th>Gambar About</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Nama Perusahaan</th>
                        <th>Deskripsi</th>
                        <th>Logo</th>
                        <th>Gambar About</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
                <tbody>
                    <tr>
                        <?php foreach ($profil as $data): ?>
                            <td><?= $data['nama_perusahaan']?></td>
                            <td><?= $data['deskripsi']?></td>
                            <td>
                                <?php if (!empty($data['logo'])): ?>
                                    <img src="<?= base_url('assets/img/' . $data['logo']); ?>" width="50" alt="<?= $data['logo'] ?>" 
                                        class="clickable-image" data-id="<?= $data['id_profil_perusahaan'] ?>" 
                                        data-gambar="<?= $data['logo'] ?>" data-toggle="modal" data-target="#editLogoModal">
                                <?php else: ?>
                                    <button class="btn btn-warning btn-circle edit-logo-btn"
                                            data-id="<?= $data['id_profil_perusahaan'] ?>"
                                            data-gambar="<?= $data['logo'] ?>"
                                            data-toggle="modal" data-target="#editLogoModal">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($data['gambar_about'])): ?>
                                    <img src="<?= base_url('assets/img/' . $data['gambar_about']); ?>" width="50" alt="<?= $data['gambar_about'] ?>" 
                                        class="clickable-image" data-id="<?= $data['id_profil_perusahaan'] ?>" 
                                        data-gambar="<?= $data['gambar_about'] ?>" data-toggle="modal" data-target="#editAboutModal">
                                <?php else: ?>
                                    <button class="btn btn-warning btn-circle edit-logo-btn"
                                            data-id="<?= $data['id_profil_perusahaan'] ?>"
                                            data-gambar="<?= $data['gambar_about'] ?>"
                                            data-toggle="modal" data-target="#editAboutModal">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-circle btn-edit" 
                                    data-id="<?= $data['id_profil_perusahaan'] ?>" 
                                    data-nama="<?= $data['nama_perusahaan'] ?>" 
                                    data-deskripsi="<?= $data['deskripsi'] ?>" 
                                    data-toggle="modal" data-target="#editProfilModal">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
<!-- /.container-fluid -->
<!-- End of Main Content -->

<!-- Modal Edit Data Perusahaan -->
<div class="modal fade" id="editProfilModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Perusahaan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/simpanDataPerusahaan') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_profil_perusahaan" id="id_profil_perusahaan">
                    <div class="form-group">
                        <label>Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" required oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" required oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Logo -->
<div class="modal fade" id="editLogoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Logo</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/uploadLogo') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_profil_perusahaan" id="edit_logo_id">
                    
                    <div class="form-group">
                        <label>Gambar Saat Ini</label><br>
                        <img id="edit_logo_preview" width="100">
                    </div>
                    
                    <div class="form-group">
                        <label>Ganti Gambar</label>
                        <input type="file" name="logo" class="form-control">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Gambar -->
<div class="modal fade" id="editAboutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Gambar</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/uploadGambar') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_profil_perusahaan" id="edit_about_id">
                    <div class="form-group">
                        <label>Gambar Saat Ini</label><br>
                        <img id="edit_about_preview" width="100">
                    </div>
                    <div class="form-group">
                        <label>Ganti Gambar</label>
                        <input type="file" name="gambar_about" class="form-control">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Event untuk tombol edit profil
        document.querySelectorAll(".btn-edit").forEach(function (btn) {
            btn.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let nama = this.getAttribute("data-nama");
                let deskripsi = this.getAttribute("data-deskripsi");

                document.getElementById("id_profil_perusahaan").value = id;
                document.getElementById("nama_perusahaan").value = nama;
                document.getElementById("deskripsi").value = deskripsi;

                $("#editProfilModal").modal("show");
            });
        });

        // Event untuk klik gambar logo
        document.querySelectorAll(".clickable-image[data-target='#editLogoModal']").forEach(function (img) {
            img.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let gambar = this.getAttribute("data-gambar");

                document.getElementById("edit_logo_id").value = id;
                document.getElementById("edit_logo_preview").src = "<?= base_url('assets/img/') ?>" + gambar;

                $("#editLogoModal").modal("show");
            });
        });

        document.querySelectorAll(".clickable-image[data-target='#editAboutModal']").forEach(function (img) {
            img.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let gambar = this.getAttribute("data-gambar");

                document.getElementById("edit_about_id").value = id;
                document.getElementById("edit_about_preview").src = "<?= base_url('assets/img/') ?>" + gambar;

                $("#editAboutModal").modal("show");
            });
        });
    });
</script>
<?= $this->endSection(); ?>