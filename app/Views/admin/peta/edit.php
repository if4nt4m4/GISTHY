<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Edit Data Tanah</h1>

<!-- Form Edit Data -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Data</h6>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/admin/update/' . $tanah['id_tanah']) ?>" method="post">
            <div class="form-group">
                <label for="kawasan">Penggunaan Tanah</label>
                <select name="kawasan" id="kawasan" class="form-control" required>
                    <option value="">-- Pilih Jenis Penggunaan Tanah --</option>
                    <?php foreach ($penggunaan as $p): ?>
                        <option value="<?= $p['penggunaan']; ?>" <?= ($tanah['kawasan'] == $p['penggunaan']) ? 'selected' : '' ?>>
                            <?= $p['penggunaan']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="indikasi">Indikasi Tanah <span class="text-danger font-weight-bold">*</span></label>
                <select type="text" class="form-control" id="indikasi" name="indikasi" required oninvalid="this.setCustomValidity('Kolom ini belum dipilih!')" oninput="this.setCustomValidity('')">
                    <option value="" disabled <?= ($tanah['indikasi'] == '') ? 'selected' : '' ?>>Pilih Indikasi Tanah</option>
                    <option value="nilai pasar tanah" <?= ($tanah['indikasi'] == 'nilai pasar tanah') ? 'selected' : '' ?>>Nilai Pasar Tanah</option>
                    <option value="nilai tanah data pembanding" <?= ($tanah['indikasi'] == 'nilai tanah data pembanding') ? 'selected' : '' ?>>Nilai Tanah Data Pembanding</option>
                    <option value="permeter tanah data pembanding" <?= ($tanah['indikasi'] == 'permeter tanah data pembanding') ? 'selected' : '' ?>>Permeter Tanah Data Pembanding</option>
                </select>
            </div>
            <!-- Gabungan Koordinat: Titik Referensi (readonly) -->
            <div class="form-group">
                <label for="titik_referensi">Titik Data Nilai/Pembanding (Latitude, Longitude)</label>
                <input type="text" class="form-control" id="titik_referensi" name="titik_referensi" readonly>
            </div>
            <!-- Titik Latitude -->
            <div class="form-group">
                <label for="titik_latitude">Titik Latitude <span class="text-danger font-weight-bold">*</span></label>
                <input type="text" class="form-control" id="titik_latitude" name="titik_latitude" value="<?= $tanah['titik_latitude'] ?>" required oninput="updateKoordinatData()">
            </div>
            <!-- Titik Longitude -->
            <div class="form-group">
                <label for="titik_longitude">Titik Longitude <span class="text-danger font-weight-bold">*</span></label>
                <input type="text" class="form-control" id="titik_longitude" name="titik_longitude" value="<?= $tanah['titik_longitude'] ?>" required oninput="updateKoordinatData()">
            </div>
            <div class="form-group">
                <label for="kelurahan">Desa/Kelurahan</label>
                <input type="text" class="form-control" id="kelurahan" name="kelurahan" value="<?= $tanah['kelurahan'] ?>" required>
            </div>
            <div class="form-group">
                <label for="kecamatan">Kecamatan</label>
                <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="<?= $tanah['kecamatan'] ?>" required>
            </div>
            <div class="form-group">
                <label for="wilayah">Wilayah</label>
                <input type="text" class="form-control" id="wilayah" name="wilayah" value="<?= $tanah['wilayah'] ?>" required>
            </div>            
            <div class="form-group">
                <label for="waktu_terkini">Tanggal/Tahun Dinilai</label>
                <input type="text" class="form-control" id="waktu_terkini" value="<?= $tanah['waktu_terkini'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="harga_terkini">Harga</label>
                <input type="text" class="form-control" id="harga_terkini" value="<?= number_format($tanah['harga_terkini'], 2, ',', '.') ?>" readonly>
            </div>
            <div class="form-group">
                <label for="luas_tanah">Luas Tanah <span class="text-danger font-weight-bold">*</span></label>
                <div class="input-group">
                    <input type="number" class="form-control" id="luas_tanah" name="luas_tanah" value="<?= $tanah['luas_tanah'] ?>" required >
                    <div class="input-group-append">
                        <span class="input-group-text">/m²</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="luas_bangunan">Luas Bangunan <span class="text-danger font-weight-bold">*</span></label>
                <div class="input-group">
                    <input type="number" class="form-control" id="luas_bangunan" name="luas_bangunan" value="<?= $tanah['luas_bangunan'] ?>" required >
                    <div class="input-group-append">
                        <span class="input-group-text">/m²</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="nama_pemilik">Nama Pemilik/Agen <span class="text-danger font-weight-bold">*</span></label>
                <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik" value="<?= $tanah['nama_pemilik'] ?>" required >
            </div>
            <div class="form-group">
                <label for="no_telp">No Telepon <span class="text-danger font-weight-bold">*</span></label>
                <input type="tel" class="form-control" id="no_telp" name="no_telp" 
                    placeholder="Masukkan dengan 08******** atau -" 
                    required 
                    maxlength="13" 
                    pattern="^08[0-9]{9,11}$|^-$" 
                    value="<?= $tanah['no_telp'] ?>">
            </div>            
           <button type="submit" class="btn btn-primary">Simpan Data</button>
        </form>
    </div>
</div>

</div>
<!-- /.container-fluid -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        updateKoordinatData(); // inisialisasi gabungan koordinat
    });

    function updateKoordinatData() {
        const lat = document.getElementById('titik_latitude').value.trim();
        const long = document.getElementById('titik_longitude').value.trim();
        const referensiInput = document.getElementById('titik_referensi');

        if (lat && long) {
            referensiInput.value = `${lat}, ${long}`;
        } else {
            referensiInput.value = '';
        }
    }
</script>

<?= $this->endSection(); ?>
