<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Riwayat Harga</h1>
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
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Grafik Riwayat Harga</h6>
        </div>
        <div class="card-body">
            <!-- Form Filter -->
            <form method="get" action="<?= base_url('admin/riwayatHarga') ?>" class="mb-4">
                <div class="form-row">
                    <div class="form-group col-md-8 shadow">
                        <label for="filter">Cari Berdasarkan Wilayah, Kecamatan, atau Kelurahan</label>
                        <input type="text" class="form-control" name="filter" id="filter" 
                            placeholder="Masukkan Wilayah, Kecamatan, atau Kelurahan" 
                            value="<?= $filter ?? '' ?>" list="suggestions" autocomplete="off">
                        <div id="suggestion-box" class="dropdown-menu w-100 shadow"></div>
                    </div>
                    <div class="form-group col-md-4 align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="<?= base_url('admin/riwayatHarga') ?>" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
            <!-- Canvas untuk Grafik -->
            <canvas id="riwayatHargaChart" width="400" height="100"></canvas>
        </div>
    </div>
</div>

<!-- Sertakan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Ambil data dari controller
    var labels = <?= $labels ?? '[]' ?>; // Gunakan array kosong jika $labels tidak terdefinisi
    var dataHarga = <?= $dataHarga ?? '[]' ?>; // Gunakan array kosong jika $dataHarga tidak terdefinisi

    // Buat grafik hanya jika ada data
    if (labels.length > 0 && dataHarga.length > 0) {
        var ctx = document.getElementById('riwayatHargaChart').getContext('2d');
        var riwayatHargaChart = new Chart(ctx, {
            type: 'line', // Jenis grafik (line chart)
            data: {
                labels: labels, // Sumbu X (tanggal)
                datasets: [{
                    label: 'Riwayat Harga',
                    data: dataHarga, // Sumbu Y (harga)
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } else {
        // Tampilkan pesan jika tidak ada data
        document.getElementById('riwayatHargaChart').insertAdjacentHTML('afterend', '<p class="text-center">Tidak ada data riwayat harga yang tersedia.</p>');
    }
    document.addEventListener("DOMContentLoaded", function () {
        let inputFilter = document.getElementById("filter");
        let suggestionBox = document.getElementById("suggestion-box");

        let wilayahList = <?= json_encode(array_column($wilayahList, 'wilayah')) ?>;
        let kecamatanList = <?= json_encode(array_column($kecamatanList, 'kecamatan')) ?>;
        let kelurahanList = <?= json_encode(array_column($kelurahanList, 'kelurahan')) ?>;

        let allSuggestions = [...new Set([...wilayahList, ...kecamatanList, ...kelurahanList])];

        inputFilter.addEventListener("input", function () {
            let query = this.value.toLowerCase();
            suggestionBox.innerHTML = ""; // Kosongkan sebelum menampilkan hasil baru

            if (query.length > 0) {
                let filteredSuggestions = allSuggestions.filter(item => 
                    item.toLowerCase().includes(query)
                ).slice(0, 5); // Batasi hanya 5 saran

                if (filteredSuggestions.length > 0) {
                    suggestionBox.classList.add("show"); // Tampilkan dropdown
                    filteredSuggestions.forEach(suggestion => {
                        let item = document.createElement("a");
                        item.href = "#";
                        item.classList.add("dropdown-item");
                        item.textContent = suggestion;
                        item.onclick = function (e) {
                            e.preventDefault();
                            inputFilter.value = suggestion;
                            suggestionBox.classList.remove("show"); // Sembunyikan setelah dipilih
                        };
                        suggestionBox.appendChild(item);
                    });
                } else {
                    suggestionBox.classList.remove("show");
                }
            } else {
                suggestionBox.classList.remove("show");
            }
        });

        // Sembunyikan dropdown jika klik di luar
        document.addEventListener("click", function (event) {
            if (!inputFilter.contains(event.target) && !suggestionBox.contains(event.target)) {
                suggestionBox.classList.remove("show");
            }
        });
    });
</script>

<?= $this->endSection(); ?>