<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Peta</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="font-weight-bold text-primary">Data untuk Keperluan Peta</h6>
        </div>
        <div class="card-body">
            <div id="map" style="height: 600px;"></div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/@turf/turf@6.5.0/turf.min.js"></script>
<script src="https://unpkg.com/chroma-js/chroma.min.js"></script>


<script>
    var map = L.map('map').setView([-7.625, 111.523], 13);

    // Tambahkan layer OpenStreetMap (OSM)
    var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    });

    // Tambahkan layer ArcGIS World Imagery (Satelit)
    var arcgisLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; Esri, Maxar, Earthstar Geographics, and the GIS User Community'
    });

    // Tambahkan peta OSM sebagai default
    osmLayer.addTo(map);

    // Objek untuk menyimpan opsi peta
    var baseMaps = {
        "Peta Standar (OSM)": osmLayer,
        "Peta Satelit (ArcGIS)": arcgisLayer
    };

    // Tambahkan kontrol untuk mengganti peta
    L.control.layers(baseMaps).addTo(map);

    // mendefinisikan harga tertinggi dan terendah sebelum dipanggil di legend
    var hargaMin = <?= $hargaMin; ?>; // Harga terendah dari database
    var hargaMax = <?= $hargaMax; ?>; // Harga tertinggi dari database

    // Fungsi untuk mendapatkan warna berdasarkan harga tanah
    function getColorHarga(harga, hargaMax, hargaMin) {
        if (hargaMax === hargaMin) {
            return '#65000b'; // Jika semua harga sama, gunakan satu warna default
        }
        //fungsi memberikan warna
        var scale = chroma.scale(['#ffcc00', '#feb24c', '#fd8d3c', '#fc4e2a', '#e31a1c', '#b10026']) // Warna lebih variatif
            .domain([hargaMin, hargaMin + (hargaMax - hargaMin) * 0.2, hargaMin + (hargaMax - hargaMin) * 0.4, hargaMin + (hargaMax - hargaMin) * 0.6, hargaMin + (hargaMax - hargaMin) * 0.8, hargaMax]);

        return scale(harga).hex();
    }

    // kode legend warna
    var legend = L.control({ position: "bottomright" });

    //fungsi warna pada legend
    legend.onAdd = function (map) {
        var div = L.DomUtil.create("div", "info legend")
            var grades = [
                hargaMin,
                hargaMin + (hargaMax - hargaMin) * 0.2,
                hargaMin + (hargaMax - hargaMin) * 0.4,
                hargaMin + (hargaMax - hargaMin) * 0.6,
                hargaMin + (hargaMax - hargaMin) * 0.8,
                hargaMax
            ];

        div.innerHTML = '<strong>Keterangan Harga Tanah</strong><br>';

        for (var i = 0; i < grades.length; i++) {
            var from = grades[i];
            var to = grades[i + 1];

            div.innerHTML +=
                '<div style="display: flex; align-items: center; margin-bottom: 5px;">' +
                '<i style="background:' + getColorHarga(from, hargaMax, hargaMin) + '; width: 25px; height: 15px; display: inline-block; margin-right: 8px;"></i>' +
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

    // ===================== STYLING TAMBAHAN ===================== //
    var styleElement = document.createElement('style');
    styleElement.innerHTML = `
        .popup-card {
            font-size: 14px;
        }
        .popup-card h5 {
            margin-top: 0;
            margin-bottom: 8px;
            font-size: 16px;
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }
        .popup-card table {
            width: 100%;
        }
        .popup-card table td {
            padding: 3px 6px;
            vertical-align: top;
        }
        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 8px;
            background: #fff;
            padding: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
    `;
    document.head.appendChild(styleElement);

    // ===================== FUNGSI POPUP ===================== //
    function createPopupContent(properties, type = 'polygon') {
        // Format harga untuk kedua tipe (polygon dan marker)
        const formattedPrice = properties.harga_terkini ? 
            new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2
            }).format(properties.harga_terkini) : '-';

        if (type === 'polygon') {
            return `
                <div class="popup-card">
                    <h5>${properties.penggunaan}</h5>
                    <table>
                        <tr><td><strong>Penggunaan:</strong></td><td>${properties.penggunaan ?? '-'}</td></tr>
                        <tr><td><strong>Kelurahan:</strong></td><td>${properties.kelurahan}</td></tr>
                        <tr><td><strong>Kecamatan:</strong></td><td>${properties.kecamatan}</td></tr>
                        <tr><td><strong>Wilayah:</strong></td><td>${properties.wilayah}</td></tr>
                        <tr><td><strong>Waktu Terkini:</strong></td><td>${properties.waktu_terkini}</td></tr>
                        <tr><td><strong>Harga Terkini:</strong></td><td>${formattedPrice}</td></tr>
                    </table>
                </div>
            `;
        } else if (type === 'marker') {
            return `
                <div class="popup-card">
                    <h5>Lokasi Titik Objek/Pembanding</h5>
                    <table>
                        <tr><td><strong>Penggunaan:</strong></td><td>${properties.penggunaan ?? '-'}</td></tr>
                        <tr><td><strong>Indikasi:</strong></td><td> Indikasi ${properties.indikasi ?? '-'}</td></tr>
                        <tr><td><strong>Koordinat:</strong></td><td>${properties.latitude.toFixed(6)}, ${properties.longitude.toFixed(6)}</td></tr>
                        <tr><td><strong>Kecamatan:</strong></td><td>${properties.kecamatan}</td></tr>
                        <tr><td><strong>Kelurahan:</strong></td><td>${properties.kelurahan}</td></tr>
                        <tr><td><strong>Wilayah:</strong></td><td>${properties.wilayah}</td></tr>
                        <tr><td><strong>Luas Tanah:</strong></td><td>${properties.luas_tanah ?? '-'}</td></tr>
                        <tr><td><strong>Luas Bangunan:</strong></td><td>${properties.luas_bangunan ?? '-'}</td></tr>
                        <tr><td><strong>Waktu Terkini:</strong></td><td>${properties.waktu_terkini ?? '-'}</td></tr>
                        <tr><td><strong>Harga Terkini:</strong></td><td>${formattedPrice}</td></tr>
                        <tr><td><strong>Nama Pemilik:</strong></td><td>${properties.nama_pemilik ?? '-'}</td></tr>                        
                        <tr><td><strong>No Telepon:</strong></td><td>${properties.no_telp ?? '-'}</td></tr>
                    </table>
                </div>
            `;
        }
    }

    // ===================== LAYER TANAH ===================== //
    var geoJsonData = <?= $geoJsonData; ?>;
    var tanahLayer;

    function loadData(data) {
        if (tanahLayer) {
            map.removeLayer(tanahLayer);
        }

        tanahLayer = L.geoJson(data, {
            style: function (feature) {
                return {
                    fillColor: getColorHarga(feature.properties.harga_terkini, hargaMax, hargaMin),
                    weight: 1,
                    opacity: 1,
                    color: 'white',
                    fillOpacity: 0.7
                };
            },
            onEachFeature: function (feature, layer) {
                const popupContent = createPopupContent(feature.properties, 'polygon');
                layer.bindPopup(popupContent, { className: 'custom-popup' });
            }
        }).addTo(map);
    }

    loadData(geoJsonData);

    // ===================== TITIK REFERENSI ===================== //
    var titikReferensiData = <?= $titikReferensiData; ?>;
    var referensiMarkers = [];

    function addTitikReferensi(data) {
        data.features.forEach(function (feature) {
            var coords = feature.geometry.coordinates;
            var props = feature.properties;

            var marker = L.marker([coords[1], coords[0]]).bindPopup(
                createPopupContent({
                    penggunaan: props.penggunaan,
                    indikasi: props.indikasi,                    
                    latitude: coords[1],
                    longitude: coords[0],
                    kelurahan: props.kelurahan,
                    kecamatan: props.kecamatan,
                    wilayah: props.wilayah,
                    luas_tanah: props.luas_tanah,
                    luas_bangunan: props.luas_bangunan,
                    waktu_terkini: props.waktu_terkini,
                    harga_terkini: props.harga_terkini,
                    nama_pemilik: props.nama_pemilik,
                    no_telp: props.no_telp,
                }, 'marker'),
                { className: 'custom-popup' }
            );

            referensiMarkers.push(marker);

            if (map.getZoom() >= 18) {
                marker.addTo(map);
            }
        });
    }

    addTitikReferensi(titikReferensiData);

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

    map.fire('zoomend');   
</script>

<?= $this->endSection(); ?>