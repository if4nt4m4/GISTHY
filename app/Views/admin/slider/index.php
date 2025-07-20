<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Slider</h1>
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
        <button class="btn btn-success btn-icon-split" data-toggle="modal" data-target="#tambahSliderModal">
            <span class="icon text-white-50"><i class="bi bi-plus-circle-fill"></i></span>
            <span class="text">Tambah Slider</span>
        </button>
    </div>

    <!-- Data Slider -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Slider</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; foreach ($sliders as $data): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><img src="<?= base_url('assets/img/' . $data['gambar']); ?>" width="300"></td>
                            <td>
                                <button class="btn btn-warning btn-circle edit-slider-btn"
                                    data-id="<?= $data['id_slider'] ?>"
                                    data-gambar="<?= $data['gambar'] ?>"
                                    data-toggle="modal" data-target="#editSliderModal">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn btn-danger btn-circle delete-slider-btn"
                                    data-id="<?= $data['id_slider'] ?>"
                                    data-gambar="<?= $data['gambar'] ?>"
                                    data-toggle="modal" data-target="#deleteSliderModal">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Slider -->
<div class="modal fade" id="tambahSliderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Slider</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/simpanDataSlider') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Gambar Slider</label>
                        <input type="file" name="gambar" class="form-control" required>
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

<!-- Modal Edit Slider -->
<div class="modal fade" id="editSliderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Slider</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/editDataSlider') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_slider" id="edit_id_slider">
                    <div class="form-group">
                        <label>Gambar Saat Ini</label><br>
                        <img id="edit_gambar_preview" width="100">
                    </div>
                    <div class="form-group">
                        <label>Ganti Gambar</label>
                        <input type="file" name="gambar" class="form-control">
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

<!-- Modal Hapus Slider -->
<div class="modal fade" id="deleteSliderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus slider <strong id="delete_gambar_name"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a id="deleteSliderLink" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-slider-btn').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('edit_id_slider').value = this.dataset.id;
                document.getElementById('edit_gambar_preview').src = "<?= base_url('assets/img/') ?>" + this.dataset.gambar;
            });
        });

        document.querySelectorAll('.delete-slider-btn').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('delete_gambar_name').textContent = this.dataset.gambar;
                document.getElementById('deleteSliderLink').href = "<?= base_url('admin/hapusDataSlider/') ?>" + this.dataset.id;
            });
        });
    });
</script>

<?= $this->endSection(); ?>