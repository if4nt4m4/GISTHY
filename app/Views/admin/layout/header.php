<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

<!-- Sidebar Toggle (Topbar) -->
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
</button>

<!-- Topbar Navbar -->
<ul class="navbar-nav ml-auto">

    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
    <li class="nav-item dropdown no-arrow d-sm-none">
        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-search fa-fw"></i>
        </a>
        <!-- Dropdown - Messages -->
        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
            aria-labelledby="searchDropdown">
            <form class="form-inline mr-auto w-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small"
                        placeholder="Search for..." aria-label="Search"
                        aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>http://localhost:8080/#hero-carousel
                    </div>
                </div>
            </form>
        </div>
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= esc($user['nama']); ?></span>
            <img class="img-profile rounded-circle"
                src="<?= base_url('assets-admin/img/' . $user['gambar']); ?>" alt="<?= $user['gambar']?>">
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#profileModal">
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                Profile
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
            </a>
        </div>
    </li>
</ul>

</nav>

<!-- Modal Profil User -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Profil Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img src="<?= base_url('assets-admin/img/' . $user['gambar']); ?>" alt="<?= $user['gambar']?>" class="img-thumbnail rounded-circle" width="120">
                </div>
                <table class="table mt-3">
                    <tr><th>Nama</th><td><?= esc($user['nama']); ?></td></tr>
                    <tr><th>Username</th><td><?= esc($user['username']); ?></td></tr>
                    <tr><th>Role</th><td><?= esc($user['role']); ?></td></tr>
                    <tr><th>Status</th><td><?= esc($user['status']); ?></td></tr>
                </table>
                <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#editProfileModal" data-dismiss="modal">Ubah Profil</button>
                <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#editPasswordModal" data-dismiss="modal">Ubah Password</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Ubah Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Pesan Kesalahan / Sukses -->
                <?php if (session()->getFlashdata('profile_error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('profile_error'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('profile_success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('profile_success'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form id="profileForm" action="<?= base_url('admin/updateProfile'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_user" value="<?= $user['id_user']; ?>">
                    <input type="hidden" name="old_gambar" value="<?= $user['gambar']; ?>">
                    <input type="hidden" name="gambar_changed" id="gambarChanged" value="0">
                    
                    <!-- Preview Gambar -->
                    <div class="text-center mb-4">
                        <img id="imagePreview" src="<?= base_url('assets-admin/img/' . $user['gambar']); ?>" 
                             alt="Preview Gambar" class="img-thumbnail rounded-circle" 
                             style="width: 50px; height: 50px; object-fit: cover;">
                    </div>
                    
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= esc($user['nama']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="username_profile">Username</label>
                        <input type="text" class="form-control" id="username_profile" name="username" value="<?= esc($user['username']); ?>" required autocomplete="username">
                    </div>
                    
                    <div class="form-group">
                        <label for="gambar">Foto Profil</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="gambar" name="gambar" onchange="handleFileSelect(this)">
                            <label class="custom-file-label" for="gambar">Pilih file...</label>
                        </div>
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto (Maks. 2MB)</small>
                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="removeImageBtn" style="display: none;">
                            <i class="fas fa-trash"></i> Hapus Foto
                        </button>
                    </div>
                    
                    <div class="form-group">
                        <label for="current_password_profile">Password Saat Ini</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password_profile" name="current_password" autocomplete="current-password" required>
                            <div class="input-group-append">
                                <button class="btn btn-secondary toggle-password" type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Diperlukan untuk mengubah profil</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Password -->
<div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPasswordModalLabel">Ubah Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Ubah Password</h5>

                <!-- Pesan Kesalahan / Sukses -->
                <?php if (session()->getFlashdata('password_error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('password_error'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('password_success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('password_success'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/changePassword'); ?>" method="post">
                    <input type="hidden" name="id_user" value="<?= $user['id_user']; ?>">
                    <input type="text" name="username" id="username_hidden" autocomplete="username" hidden>

                    <div class="form-group">
                        <label for="current_password_password">Password Saat Ini</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password_password" name="current_password" autocomplete="current-password" required>
                            <div class="input-group-append">
                                <button class="btn btn-secondary toggle-password" type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_baru">Password Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_baru" name="password_baru" autocomplete="new-password" required>
                            <div class="input-group-append">
                                <button class="btn btn-secondary toggle-password" type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Minimal 8 karakter</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="konfirmasi_password">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" autocomplete="new-password" required>
                            <div class="input-group-append">
                                <button class="btn btn-secondary toggle-password" type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-danger btn-block">Ubah Password</button>
                </form>
            </div>
        </div>
    </div>    
</div>

<!-- Script untuk Menjaga Modal Tetap Terbuka Jika Ada Error/Sukses -->
<script>
    // Fungsi untuk handle pemilihan file
    function handleFileSelect(input) {
        const preview = document.getElementById('imagePreview');
        const file = input.files[0];
        const reader = new FileReader();
        const removeBtn = document.getElementById('removeImageBtn');
        const gambarChanged = document.getElementById('gambarChanged');

        reader.onloadend = function() {
            preview.src = reader.result;
            removeBtn.style.display = 'block';
            gambarChanged.value = '1'; // Tandai bahwa gambar diubah
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    $(document).ready(function() {
        // Cek jika ada error atau sukses yang berkaitan dengan ubah password
        <?php if (session()->getFlashdata('password_error') || session()->getFlashdata('password_success')) : ?>
            $('#editPasswordModal').modal('show');
        <?php endif; ?>
        
        // Cek jika ada error atau sukses yang berkaitan dengan ubah profil
        <?php if (session()->getFlashdata('profile_error') || session()->getFlashdata('profile_success')) : ?>
            $('#editProfileModal').modal('show');
        <?php endif; ?>

        // Tampilkan/Hilangkan Password
        $(".toggle-password").click(function() {
            let input = $(this).closest(".input-group").find("input");
            let icon = $(this).find("i");

            if (input.attr("type") === "password") {
                input.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                input.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });
        
        // Update nama file pada input file
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
        
        // Tutup modal profil saat membuka modal edit
        $('[data-target="#editProfileModal"], [data-target="#editPasswordModal"]').click(function() {
            $('#profileModal').modal('hide');
        });

        // Tombol hapus gambar
        $('#removeImageBtn').click(function() {
            $('#gambar').val(''); // Kosongkan input file
            $('.custom-file-label').text('Pilih file...'); // Reset label
            $('#imagePreview').attr('src', "<?= base_url('assets-admin/img/' . $user['gambar']); ?>"); // Kembalikan ke gambar lama
            $(this).hide();
            $('#gambarChanged').val('0'); // Tandai bahwa gambar tidak diubah
        });

        // Validasi form sebelum submit
        $('#profileForm').submit(function(e) {
            const fileInput = $('#gambar')[0];
            const gambarChanged = $('#gambarChanged').val();
            
            // Jika tidak ada perubahan gambar, hapus data file dari form
            if (gambarChanged === '0') {
                const formData = new FormData(this);
                formData.delete('gambar'); // Hapus file dari form data
                
                // Submit form secara manual dengan data yang dimodifikasi
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        location.reload(); // Reload halaman setelah sukses
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            } else {
                // Jika ada file yang dipilih, validasi ukuran
                if (fileInput.files.length > 0) {
                    const fileSize = fileInput.files[0].size / 1024 / 1024; // in MB
                    if (fileSize > 2) {
                        alert('Ukuran file maksimal 2MB');
                        return false;
                    }
                }
            }
            return true;
        });
    });
</script>

<!-- End of Topbar -->