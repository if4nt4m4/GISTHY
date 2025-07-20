<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Tambah Data Tanah</h1>

<!-- Form Input Data -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Input Data</h6>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="<?= base_url('/admin/simpan') ?>" method="post">
            <div class="form-group">
                <label for="kawasan">Penggunaan Tanah <span class="text-danger font-weight-bold">*</span></label>
                <select name="kawasan" id="kawasan" class="form-control" required oninvalid="this.setCustomValidity('Kolom ini belum dipilih!')" oninput="this.setCustomValidity('')">
                    <option value="">-- Pilih Jenis Pengunaan Tanah --</option>
                    <?php foreach ($penggunaan as $p): ?>
                        <option value="<?= $p['penggunaan']; ?>"><?= $p['penggunaan']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="indikasi">Indikasi Tanah <span class="text-danger font-weight-bold">*</span></label>
                <select type="text" class="form-control" id="indikasi" name="indikasi" required oninvalid="this.setCustomValidity('Kolom ini belum dipilih!')" oninput="this.setCustomValidity('')">
                    <option value="disabled selected">Pilih Indikasi Tanah</option>
                    <option value="nilai pasar tanah">Nilai Pasar Tanah</option>
                    <option value="nilai tanah data pembanding">Nilai Tanah Data Pembanding</option>
                    <option value="permeter tanah data pembanding">Permeter Tanah Data Pembanding</option>
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
                <input type="text" class="form-control" id="titik_latitude" name="titik_latitude" placeholder="Contoh: -7.6291" required oninput="updateKoordinatData()">
            </div>
            <!-- Titik Longitude -->
            <div class="form-group">
                <label for="titik_longitude">Titik Longitude <span class="text-danger font-weight-bold">*</span></label>
                <input type="text" class="form-control" id="titik_longitude" name="titik_longitude" placeholder="Contoh: 111.5234" required oninput="updateKoordinatData()">
            </div>
            <div class="form-group">
                <label for="kelurahan">Desa/Kelurahan <span class="text-danger font-weight-bold">*</span></label>
                <input type="text" class="form-control" id="kelurahan" name="kelurahan" required oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')">
            </div>
            <div class="form-group">
                <label for="kecamatan">Kecamatan <span class="text-danger font-weight-bold">*</span></label>
                <input type="text" class="form-control" id="kecamatan" name="kecamatan" required oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')">
            </div>
            <div class="form-group">
                <label for="wilayah">Wilayah <span class="text-danger font-weight-bold">*</span></label>
                <input type="text" class="form-control" id="wilayah" name="wilayah" required oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')">
            </div>          
            <div class="form-group">
                <label for="waktu_terkini">Tanggal/Tahun Dinilai <span class="text-danger font-weight-bold">*</span></label>
                <input type="date" class="form-control" id="waktu" name="waktu_terkini" required oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')">
            </div>
            <div class="form-group">
                <label for="harga_terkini">Harga <span class="text-danger font-weight-bold">*</span></label>
                <div class="input-group">
                    <input type="number" class="form-control" id="harga" name="harga_terkini" placeholder="Harga Per Meter" required oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')">
                    <div class="input-group-append">
                        <span class="input-group-text">/m²</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="luas_tanah">Luas Tanah <span class="text-danger font-weight-bold">*</span></label>
                <div class="input-group">
                    <input type="number" class="form-control" id="luas_tanah" name="luas_tanah" placeholder="Luas tanah" required oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')">
                    <div class="input-group-append">
                        <span class="input-group-text">/m²</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="luas_bangunan">Luas Bangunan <span class="text-danger font-weight-bold">*</span></label>
                <div class="input-group">
                    <input type="number" class="form-control" id="luas_bangunan" name="luas_bangunan" placeholder="Luas Bangunan" required oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')">
                    <div class="input-group-append">
                        <span class="input-group-text">/m²</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="nama_pemilik">Nama Pemilik/Agen <span class="text-danger font-weight-bold">*</span></label>
                <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik" required oninvalid="this.setCustomValidity('Kolom ini belum diisi!')" oninput="this.setCustomValidity('')">
            </div>
            <div class="form-group">
                <label for="no_telp">No Telepon <span class="text-danger font-weight-bold">*</span></label>
                <input type="tel" class="form-control" id="no_telp" name="no_telp" 
                    placeholder="Masukkan dengan 08********" 
                    required 
                    maxlength="13" 
                    pattern="^08[0-9]{9,11}$|^-$" 
                    oninvalid="this.setCustomValidity('Masukkan nomor telepon yang valid!')" 
                    oninput="this.setCustomValidity('')">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
        </form>
    </div>
</div>

</div>
<!-- /.container-fluid -->

<script>
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