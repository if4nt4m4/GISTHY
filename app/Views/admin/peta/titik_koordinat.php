<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800"><?= isset($editMode) && $editMode ? 'Edit Titik Koordinat' : 'Tambah Titik Koordinat' ?></h1>

<!-- LeafletJS Map -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pilih Titik Koordinat</h6>
        <h6 class="font-weight-normal my-2">Data <span class="text-info"><?= $tanah['kawasan']?></span> pada Kelurahan/Desa <span class="text-info"><?= $tanah['kelurahan']?></span> di Kecamatan <span class="text-info"><?= $tanah['kecamatan']?> <?= $tanah['wilayah']?></span></h6>
        <div class="alert alert-info mt-2">
            <strong>Titik Referensi:</strong> Titik merah adalah lokasi tanah/rumah yang dinilai (hanya sebagai penanda). Silakan tambahkan titik baru untuk membentuk polygon batas tanah.
        </div>
    </div>
    <div class="card-body">
        <div id="map" style="height: 500px;"></div>
        <form action="<?= isset($editMode) && $editMode ? base_url('admin/updateKoordinat/' . $id_tanah) : base_url('admin/simpanKoordinat') ?>" method="POST">
            <?= csrf_field()?>
            <!-- Input untuk titik referensi (hidden) -->
            <input type="hidden" name="titik_latitude" value="<?= $tanah['titik_latitude'] ?? 0 ?>">
            <input type="hidden" name="titik_longitude" value="<?= $tanah['titik_longitude'] ?? 0 ?>">
            
            <div id="coordinates-container">
                <!-- Input fields for latitude and longitude will be added here dynamically -->
            </div>
            <br>
            <button type="submit" class="btn btn-primary"><?= isset($editMode) && $editMode ? 'Simpan Koordinat' : 'Simpan Koordinat' ?></button>
            <button type="button" class="btn btn-danger" id="reset-coordinates">Reset Koordinat Polygon</button>
        </form>
    </div>
</div>

</div>
<!-- /.container-fluid -->

<!-- Include LeafletJS CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<!-- Include Leaflet Control Geocoder -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([-7.625, 111.523], 11);

        var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });

        var arcgisLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; <a href="https://www.esri.com/">Esri</a> | <a href="https://www.arcgis.com/">ArcGIS</a>',
            maxZoom: 19
        });

        osmLayer.addTo(map);

        var baseMaps = {
            "Peta Standar (OSM)": osmLayer,
            "Peta Satelit (ArcGIS)": arcgisLayer
        };

        L.control.layers(baseMaps).addTo(map);
        L.Control.geocoder({ defaultMarkGeocode: true }).addTo(map);

        var editableLayers = new L.FeatureGroup();
        map.addLayer(editableLayers);

        var polygonLayer = null; // Untuk menyimpan objek polygon

        // Titik referensi (hanya sebagai penanda visual, tidak mempengaruhi polygon)
        var refLat = <?= $tanah['titik_latitude'] ?? 0 ?>;
        var refLng = <?= $tanah['titik_longitude'] ?? 0 ?>;
        
        // Tambahkan titik referensi jika valid
        if (refLat != 0 && refLng != 0) {
            var refMarker = L.marker([refLat, refLng], {
                icon: L.divIcon({
                    className: 'reference-marker',
                    html: '<div style="background-color: red; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white;"></div>',
                    iconSize: [16, 16]
                }),
                draggable: false
            }).addTo(map);
            
            refMarker.bindPopup("<b>Titik Referensi</b><br>Lokasi tanah/rumah yang dinilai").openPopup();
            
            // Pusatkan peta ke titik referensi
            map.setView([refLat, refLng], 15);
        }

        var points = <?= isset($titikKoordinat) ? json_encode(array_filter($titikKoordinat, function($point) {
            return $point['latitude'] != 0.00000 && $point['longitude'] != 0.00000;
        })) : '[]' ?>;

        // Jika ada titik dari database, tambahkan ke peta
        if (points.length > 0) {
            var latlngs = [];
            points.forEach(function(point) {
                var marker = L.marker([point.latitude, point.longitude], { draggable: true }).addTo(editableLayers);
                latlngs.push([point.latitude, point.longitude]);
                marker.on('dragend', updateCoordinatesForm);
                marker.on('click', function() {
                    editableLayers.removeLayer(marker);
                    updateCoordinatesForm();
                });
            });
            // Gambar polygon awal (hanya dari titik-titik polygon, tanpa titik referensi)
            drawPolygon(latlngs);
            updateCoordinatesForm();
        }

        function onMapClick(e) {
            var marker = L.marker(e.latlng, { draggable: true }).addTo(editableLayers);

            marker.on('dragend', updateCoordinatesForm);
            marker.on('click', function() {
                editableLayers.removeLayer(marker);
                updateCoordinatesForm();
            });

            updateCoordinatesForm();
        }

        function updateCoordinatesForm() {
            var container = document.getElementById('coordinates-container');
            container.innerHTML = '';

            var latlngs = [];

            editableLayers.eachLayer(function(layer) {
                var latlng = layer.getLatLng();
                if (latlng.lat !== 0.00000 && latlng.lng !== 0.00000) {
                    latlngs.push([latlng.lat, latlng.lng]);

                    var latitudeInput = document.createElement('input');
                    latitudeInput.type = 'hidden';
                    latitudeInput.name = 'latitude[]';
                    latitudeInput.value = latlng.lat;

                    var longitudeInput = document.createElement('input');
                    longitudeInput.type = 'hidden';
                    longitudeInput.name = 'longitude[]';
                    longitudeInput.value = latlng.lng;

                    container.appendChild(latitudeInput);
                    container.appendChild(longitudeInput);
                }
            });

            // Update polygon (hanya dari titik-titik polygon, tanpa titik referensi)
            drawPolygon(latlngs);
        }

        function drawPolygon(latlngs) {
            if (polygonLayer) {
                map.removeLayer(polygonLayer); // Hapus polygon sebelumnya
            }
            if (latlngs.length > 2) { 
                polygonLayer = L.polygon(latlngs, {
                    color: 'blue',
                    fillOpacity: 0.5,
                    weight: 2
                }).addTo(map); // Gambar polygon
                
                // Hitung luas area (approximate)
                if (typeof L.GeometryUtil !== 'undefined' && latlngs.length > 2) {
                    var area = L.GeometryUtil.geodesicArea(latlngs);
                    var areaText = 'Luas: ' + (area / 10000).toFixed(2) + ' hektar';
                    polygonLayer.bindPopup(areaText).openPopup();
                }
            }
        }

        map.on('click', onMapClick);

        document.getElementById('reset-coordinates').addEventListener('click', function() {
            editableLayers.clearLayers();
            if (polygonLayer) {
                map.removeLayer(polygonLayer);
                polygonLayer = null;
            }
            updateCoordinatesForm();
        });
    });
</script>

<style>
    .reference-marker {
        background: none !important;
        border: none !important;
    }
</style>

<?= $this->endSection(); ?>