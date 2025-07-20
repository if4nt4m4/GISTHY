<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Penggunaan Tanah</h1>
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

    <!-- Tombol Tambah Data -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-success btn-icon-split" data-toggle="modal" data-target="#tambahPenggunaanModal">
            <span class="icon text-white-50"><i class="bi bi-plus-circle-fill"></i></span>
            <span class="text">Tambah Data</span>
        </button>
    </div>

    <!-- Data Penggunaan Tanah -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Penggunaan Tanah</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Penggunaan Tanah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; foreach ($penggunaan as $data): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['penggunaan'] ?></td>
                            <td>
                                <button class="btn btn-danger btn-circle delete-btn"
                                    data-id="<?= $data['id_penggunaan'] ?>"
                                    data-penggunaan="<?= $data['penggunaan'] ?>"
                                    data-toggle="modal" data-target="#deletePenggunaanModal">
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

<!-- Modal Tambah Data -->
<div class="modal fade" id="tambahPenggunaanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Penggunaan Tanah</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/simpanDataPenggunaan') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Penggunaan Tanah</label>
                        <input type="text" name="penggunaan" class="form-control" required>
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

<!-- Modal Hapus Data -->
<div class="modal fade" id="deletePenggunaanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus <strong id="jenis_penggunaan_tanah"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a id="deletePenggunaanLink" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Konfirmasi Hapus -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('jenis_penggunaan_tanah').textContent = this.dataset.penggunaan;
                document.getElementById('deletePenggunaanLink').href = "<?= base_url('admin/hapusDataPenggunaan/') ?>" + this.dataset.id;
            });
        });
    });
</script>

<?= $this->endSection(); ?>