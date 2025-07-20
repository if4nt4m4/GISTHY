<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Search Peta</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">        
            <h6 class="font-weight-bold text-primary">Peta dengan Menggunakan Fitur Search</h6>
            <!-- Form Pencarian -->
            <form action="<?= base_url('admin/riwayatHargaKelurahan') ?>" method="get" style="max-width: 400px;">
                <div class="input-group" style="position: relative;">
                    <input type="text" id="searchInput" name="searchInput" class="form-control" placeholder="Cari Kelurahan" value="<?= $searchKeyword ?? '' ?>" autocomplete="off">
                    <button type="submit" class="btn btn-primary mx-2" id="btnCari">Cari</button>
                </div>
                <div id="recommendationList" class="mt-2" style="display: none;">
                    <h6 class="font-weight-bold text-primary">Rekomendasi</h6>
                    <ul id="recommendationItems" class="list-group"></ul>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Elemen HTML untuk peta -->
            <div id="map" style="height: 600px;"></div>
            <h1 class="h3 mb-2 text-gray-800 mt-4">Data dari Peta</h1>
            <h6 class="m-0 font-weight-bold text-primary" id="daerahPeta">Data yang ada pada Peta</h6>
            <div class="table-responsive my-4">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Penggunaan Tanah</th>
                            <th>Indikasi</th>
                            <th>Desa/Kelurahan</th>
                            <th>Kecamatan</th>
                            <th>Kabupaten/Kota</th>
                            <th>Tgl/Th Baru</th>
                            <th>Harga Terkini</th>
                            <th>Luas Tanah</th>
                            <th>Luas Bangunan</th>
                            <th>Nama Pemilik/Agen</th>
                            <th>No Telepon</th>                             
                            <th>Riwayat Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($tanah as $data): ?>
                            <?php if (isset($data['coordinates']) && !empty($data['coordinates'])): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= isset($data['kawasan']) ? $data['kawasan'] : '-' ?></td>
                                    <td class="text-capitalize"><?= isset($data['indikasi']) ? $data['indikasi'] : '-' ?></td>
                                    <td><?= isset($data['kecamatan']) ? $data['kecamatan'] : '-' ?></td>
                                    <td><?= isset($data['wilayah']) ? $data['wilayah'] : '-' ?></td>
                                    <td><?= isset($data['kelurahan']) ? $data['kelurahan'] : '-' ?></td>
                                    <td><?= isset($data['waktu_terkini']) ? $data['waktu_terkini'] : '-' ?></td>
                                    <td><?= isset($data['harga_terkini']) ? number_format($data['harga_terkini'], 2, ',', '.') : '-' ?></td>
                                    <td><?= isset($data['luas_tanah']) ? $data['luas_tanah'] : '-' ?></td>
                                    <td><?= isset($data['luas_bangunan']) ? $data['luas_bangunan'] : '-' ?></td>
                                    <td><?= isset($data['nama_pemilik']) ? $data['nama_pemilik'] : '-' ?></td>
                                    <td><?= isset($data['no_telp']) ? $data['no_telp'] : '-' ?></td>
                                    <td>                                
                                        <?php if (!empty($data['waktu']) && !empty($data['harga']) && isset($data['harga'], $data['waktu']) && $data['harga'] != 0.00 && $data['waktu'] != '0000-00-00'): ?>
                                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalRiwayat<?= $data['id_tanah'] ?>">
                                                <i class="bi bi-clock-history"></i> Lihat Riwayat
                                            </button>
                                        <?php elseif (isset($data['harga'], $data['waktu']) && $data['harga'] == 0.00 && $data['waktu'] == '0000-00-00'): ?>
                                            <span class="badge badge-secondary">Belum Ada Harga Terbaru</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Tidak Ada Riwayat</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan riwayat harga -->
<?php foreach ($tanah as $data): ?>
    <div class="modal fade" id="modalRiwayat<?= isset($data['id_tanah']) ? $data['id_tanah'] : '-' ?>" tabindex="-1" role="dialog" aria-labelledby="modalRiwayatLabel<?= isset($data['id_tanah']) ? $data['id_tanah'] : '-' ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRiwayatLabel<?= isset($data['id_tanah']) ? $data['id_tanah'] : '-' ?>">Riwayat Harga untuk <?= isset($data['kawasan']) ? $data['kawasan'] : '-' ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Harga</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['riwayat_harga'])): ?>
                                <?php foreach ($data['riwayat_harga'] as $riwayat): ?>
                                    <tr>
                                        <td><?= date('d-m-Y', strtotime($riwayat['waktu'])) ?></td>
                                        <td><?= number_format($riwayat['harga'], 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada riwayat harga yang tersedia.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Tangkap elemen yang dibutuhkan
        const searchForm = document.querySelector("form");
        const searchInput = document.getElementById("searchInput");
        const daerahPeta = document.getElementById("daerahPeta");
        
        // Fungsi untuk update teks
        function updateDaerahPetaText() {
            const query = searchInput.value.trim();
            if (query) {
                daerahPeta.textContent = `Data yang ada pada Peta Kelurahan ${query}`;
            } else {
                daerahPeta.textContent = "Data yang ada pada Peta";
            }
        }
        
        // Update saat form disubmit
        searchForm.addEventListener("submit", function(e) {
            updateDaerahPetaText();
            // Biarkan form submit normal
        });
        
        // Update juga saat input berubah (opsional)
        searchInput.addEventListener("input", function() {
            updateDaerahPetaText();
        });
        
        // Inisialisasi teks awal
        updateDaerahPetaText();

        // Rekomendasi pencarian
        const recommendationList = document.getElementById("recommendationList");
        const recommendationItems = document.getElementById("recommendationItems");
        const rekomendasi = <?= json_encode($allTanahData); ?>;

        searchInput.addEventListener("input", function() {
            const query = this.value.trim().toLowerCase();
            recommendationItems.innerHTML = "";

            if (query.length > 0) {
                const kelurahanSet = new Set();

                rekomendasi.forEach(function(item) {
                    if (item.kelurahan && item.kelurahan.toLowerCase().includes(query)) {
                        kelurahanSet.add(item.kelurahan);
                    }
                });

                if (kelurahanSet.size > 0) {
                    const categoryTitle = document.createElement("li");
                    categoryTitle.classList.add("list-group-item", "font-weight-bold", "text-primary");
                    categoryTitle.innerText = "Kelurahan";
                    recommendationItems.appendChild(categoryTitle);

                    kelurahanSet.forEach(function(text) {
                        const listItem = document.createElement("li");
                        listItem.classList.add("list-group-item");
                        listItem.textContent = text;

                        listItem.addEventListener("click", function() {
                            searchInput.value = text;
                            recommendationList.style.display = "none";
                            updateDaerahPetaText();                            
                        });

                        recommendationItems.appendChild(listItem);
                    });

                    recommendationList.style.display = "block";
                } else {
                    recommendationList.style.display = "none";
                }
            } else {
                recommendationList.style.display = "none";
            }
        });

        document.addEventListener("click", function(e) {
            if (!searchInput.contains(e.target) && !recommendationList.contains(e.target)) {
                recommendationList.style.display = "none";
            }
        });
    });

    // Peta
    var map = L.map('map').setView([-7.625, 111.523], 13);

    // Tambahkan layer OpenStreetMap (OSM)
    var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    });

    // Tambahkan layer ArcGIS World Imagery (Satelit)
    var arcgisLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; Esri, Maxar, Earthstar Geographics, and the GIS User Community'
    });

    osmLayer.addTo(map);

    var baseMaps = {
        "Peta Standar (OSM)": osmLayer,
        "Peta Satelit (ArcGIS)": arcgisLayer
    };

    L.control.layers(baseMaps).addTo(map);

    // Fungsi untuk mendapatkan warna berdasarkan harga tanah
    function getColorHarga(harga, hargaMax, hargaMin) {
        if (hargaMax === hargaMin) {
            return '#65000b';
        }

        var scale = chroma.scale(['#ffcc00', '#feb24c', '#fd8d3c', '#fc4e2a', '#e31a1c', '#b10026'])
            .domain([hargaMin, hargaMin + (hargaMax - hargaMin) * 0.2, hargaMin + (hargaMax - hargaMin) * 0.4, hargaMin + (hargaMax - hargaMin) * 0.6, hargaMin + (hargaMax - hargaMin) * 0.8, hargaMax]);

        return scale(harga).hex();
    }

    // Legenda
    var legend = L.control({ position: "bottomright" });

    legend.onAdd = function(map) {
        var div = L.DomUtil.create("div", "info legend");
        var grades = [
            <?= $hargaMin ?>,
            <?= $hargaMin ?> + (<?= $hargaMax ?> - <?= $hargaMin ?>) * 0.2,
            <?= $hargaMin ?> + (<?= $hargaMax ?> - <?= $hargaMin ?>) * 0.4,
            <?= $hargaMin ?> + (<?= $hargaMax ?> - <?= $hargaMin ?>) * 0.6,
            <?= $hargaMin ?> + (<?= $hargaMax ?> - <?= $hargaMin ?>) * 0.8,
            <?= $hargaMax ?>
        ];

        div.innerHTML = '<strong>Keterangan Harga Tanah</strong><br>';

        for (var i = 0; i < grades.length; i++) {
            var from = grades[i];
            var to = grades[i + 1];

            div.innerHTML +=
                '<div style="display: flex; align-items: center; margin-bottom: 5px;">' +
                '<i style="background:' + getColorHarga(from, <?= $hargaMax ?>, <?= $hargaMin ?>) + '; width: 25px; height: 15px; display: inline-block; margin-right: 8px;"></i>' +
                '<span>' + new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(from) +
                (to ? ' - ' + new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(to) + '</span></div>' : '+');
        }

        return div;
    };

    legend.addTo(map);

    // Layer untuk menampilkan poligon
    var tanahLayer;
    var geoJsonData = <?= $geoJsonData ?>;
    var hargaMin = <?= $hargaMin ?>;
    var hargaMax = <?= $hargaMax ?>;

    function loadData(data) {
        if (tanahLayer) {
            map.removeLayer(tanahLayer);
        }

        tanahLayer = L.geoJson(data, {
            style: function(feature) {
                return {
                    fillColor: getColorHarga(feature.properties.harga_terkini, hargaMax, hargaMin),
                    weight: 1,
                    opacity: 1,
                    color: 'white',
                    fillOpacity: 0.7
                };
            },
            onEachFeature: function(feature, layer) {
                const formattedPrice = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 2
                }).format(feature.properties.harga_terkini);

                layer.bindPopup(
                    `<b>Kecamatan:</b> ${feature.properties.kecamatan}<br>
                    <b>Wilayah:</b> ${feature.properties.wilayah}<br>
                    <b>Kelurahan:</b> ${feature.properties.kelurahan}<br>
                    <b>Waktu Terkini:</b> ${feature.properties.waktu_terkini}<br>
                    <b>Harga Terkini:</b> ${formattedPrice}<br>`
                );
            }
        }).addTo(map);
    }

    // Muat data awal
    loadData(geoJsonData);

    // ===================== TITIK REFERENSI ===================== //
    var titikReferensiData = <?= $titikReferensiData ?>;
    var referensiMarkers = [];

    function addTitikReferensi(data) {
        data.features.forEach(function (feature) {
            var coords = feature.geometry.coordinates;
            var props = feature.properties;

            var marker = L.marker([coords[1], coords[0]]).bindPopup(
                `<b>Kelurahan:</b> ${props.kelurahan}<br>
                <b>Kecamatan:</b> ${props.kecamatan}<br>
                <b>Pemilik:</b> ${props.nama_pemilik || '-'}<br>
                <b>Koordinat:</b> ${coords[1].toFixed(6)}, ${coords[0].toFixed(6)}`,
                { className: 'custom-popup' }
            );

            referensiMarkers.push(marker);

            if (map.getZoom() >= 18) {
                marker.addTo(map);
            }
        });
    }

    // Panggil fungsi untuk menambahkan titik referensi
    if (titikReferensiData && titikReferensiData.features && titikReferensiData.features.length > 0) {
        addTitikReferensi(titikReferensiData);
    }

    // Handle zoom level changes
    map.on('zoomend', function () {
        var zoomLevel = map.getZoom();
        referensiMarkers.forEach(function (marker) {
            if (zoomLevel < 18) {
                if (map.hasLayer(marker)) map.removeLayer(marker);
            } else {
                if (!map.hasLayer(marker)) map.addLayer(marker);
            }
        });
    });

    // Trigger zoom event untuk inisialisasi
    map.fire('zoomend');
       
</script>

<?= $this->endSection(); ?>