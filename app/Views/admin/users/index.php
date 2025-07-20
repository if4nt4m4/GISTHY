<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Pengguna</h1>
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

<!-- Tombol Tambah Pengguna -->
<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-success btn-icon-split" data-toggle="modal" data-target="#tambahPenggunaModal">
        <span class="icon text-white-50"><i class="bi bi-plus-circle-fill"></i></span>
        <span class="text">Tambah Pengguna</span>
    </button>
</div>

<!-- DataTales -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Pengguna</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php $no = 1; foreach ($users as $data): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['nama'] ?></td>
                            <td><?= $data['username'] ?></td>    
                            <td>
                                <span class="badge <?= ($data['status'] == 'active') ? 'badge-success' : 'badge-danger'; ?>">
                                    <?= $data['status'] ?>
                                </span>
                            </td>
                            <td><?= $data['role'] ?></td>
                            <td>
                                <button class="btn btn-warning btn-circle edit-user-btn"
                                    data-id="<?= $data['id_user'] ?>"
                                    data-status="<?= $data['status'] ?>"
                                    data-role="<?= $data['role'] ?>"
                                    data-toggle="modal" data-target="#editPenggunaModal">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn btn-danger btn-circle delete-user-btn"
                                    data-id="<?= $data['id_user'] ?>"
                                    data-nama="<?= $data['nama'] ?>"
                                    data-toggle="modal" data-target="#deletePenggunaModal">
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

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="tambahPenggunaModal" tabindex="-1" aria-labelledby="tambahPenggunaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPenggunaLabel">Tambah Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/simpanDataPengguna') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama <span class="text-danger font-weight-bold">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control" required oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')">
                    </div>
                    <div class="form-group">
                        <label>Username <span class="text-danger font-weight-bold">*</span></label>
                        <input type="text" id="username" name="username" class="form-control" required autocomplete="username" oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')">
                    </div>
                    <div class="form-group">
                        <label>Password <span class="text-danger font-weight-bold">*</span></label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password" oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status <span class="text-danger font-weight-bold">*</span></label>
                        <select id="status" name="status" class="form-control" required oninvalid="this.setCustomValidity('Kolom ini belum dipilih!')" oninput="this.setCustomValidity('')">
                            <option value="">-- Pilih Status --</option>
                            <?php foreach ($enum_statuses as $status) : ?>
                                <option value="<?= $status ?>"><?= ucfirst($status) ?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Role <span class="text-danger font-weight-bold">*</span></label>
                        <select id="role" name="role" class="form-control" required oninvalid="this.setCustomValidity('Kolom ini belum dipilih!')" oninput="this.setCustomValidity('')">
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($enum_roles as $role) : ?>
                                <option value="<?= $role ?>"><?= ucfirst($role) ?></option>
                            <?php endforeach;?>
                        </select>
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

<!-- Modal Edit Pengguna -->
<div class="modal fade" id="editPenggunaModal" tabindex="-1" aria-labelledby="editPenggunaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPenggunaLabel">Edit Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/editDataPengguna') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_user" id="edit_id_user">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="edit_status" class="form-control" value='<?= $data['status']?>' required>
                            <option value="">-- Pilih Status --</option>
                            <?php foreach ($enum_statuses as $status) : ?>
                                <option value="<?= $status ?>"><?= ucfirst($status) ?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" id="edit_role" class="form-control" required>
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($enum_roles as $role) : ?>
                                <option value="<?= $role ?>"><?= ucfirst($role) ?></option>
                            <?php endforeach;?>
                        </select>
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

<!-- Modal Hapus Pengguna -->
<div class="modal fade" id="deletePenggunaModal" tabindex="-1" aria-labelledby="deletePenggunaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePenggunaLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengguna <strong id="delete_user_name"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a id="deleteUserLink" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk Toggle Password dan Edit Modal -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.toggle-password').forEach(function(toggle) {
                toggle.addEventListener('click', function (e) {
                    e.preventDefault(); // Supaya button tidak submit form
                    const passwordField = this.closest('.input-group').querySelector('input[type="password"], input[type="text"]');
                    if (passwordField) {
                        const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
                        passwordField.setAttribute("type", type);
                        const icon = this.querySelector('i');
                        if (icon) {
                            icon.classList.toggle('bi-eye');
                            icon.classList.toggle('bi-eye-slash');
                        }
                    }
                });
            });
        });

        document.querySelectorAll('.edit-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                let userId = this.getAttribute('data-id');
                let status = this.getAttribute('data-status');
                let role = this.getAttribute('data-role');

                setTimeout(() => {
                    document.getElementById('edit_id_user').value = userId;
                    document.getElementById('edit_status').value = status;
                    document.getElementById('edit_role').value = role;
                }, 100);
            });
        });
        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                let userId = this.getAttribute('data-id');
                let userName = this.getAttribute('data-nama');

                document.getElementById('delete_user_name').textContent = userName;
                document.getElementById('deleteUserLink').setAttribute('href', '<?= base_url("admin/dataPengguna/delete/") ?>' + userId);
            });
        });
        $('#tambahPenggunaModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });
    });
</script>

<?= $this->endSection(); ?>
