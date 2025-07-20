<?= $this->extend('user/template/template') ?>
<?= $this->Section('content'); ?>

<!-- Add Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Add Leaflet Search CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-search/3.0.9/leaflet-search.min.css" />

<!-- TEST SLIDER img -->
<?= $this->include('user/home/slider'); ?>
<main class="main">
    <!-- About Section - Modified to be collapsible -->
    <section id="about" class="about section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2 class="text-center">Tentang Kami</h2>
        <div class="text-center mt-3">
          <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#aboutContent" aria-expanded="false" aria-controls="aboutContent" id="aboutToggle">
            <span class="show-text">Lihat Tentang Kami</span>
            <span class="hide-text d-none">Sembunyikan</span>
            <i class="bi bi-chevron-down ms-1"></i>
          </button>
        </div>
      </div><!-- End Section Title -->

      <div class="collapse" id="aboutContent">
        <div class="container">
          <div class="row gy-4">
            <?php foreach($profil as $perusahaan):?>
            <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
              <img src="<?= base_url('assets/img/' . $perusahaan['gambar_about']) ?>" class="img-fluid" alt="About Image">
            </div>
            <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="200">
              <p><?php echo $perusahaan['deskripsi']?></p>
            </div>
            <?php endforeach;?>
          </div>
        </div>
      </div>
    </section><!-- /About Section -->

    <!-- Statistics Section -->
    <section id="statistics" class="stats section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Data Statistik</h2>
        <p>Distribusi dan tren penggunaan lahan di wilayah Madiun</p>
      </div>
      
      <div class="container">
        <div class="row mb-4">
          <div class="col-md-4">
            <div class="stats-card">
              <h4>Luas Total Kawasan/Penggunaan Tanah</h4>
              <p class="value" id="total-area">0 Ha</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="stats-card">
              <h4>Penggunaan Tanah Terluas</h4>
              <p class="value" id="largest-area">-</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="stats-card">
              <h4>Jenis Penggunaan</h4>
              <p class="value" id="total-types">0</p>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-6">
            <div class="chart-container">
              <canvas id="landUseChart"></canvas>
            </div>
          </div>
          <div class="col-md-6">
            <div class="chart-container">
              <canvas id="areaByDistrictChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Map Section - Simplified Search -->
    <section id="map-section" class="tabs section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Peta</h2>
        <p class="mb-3">Peta ini menampilkan informasi penyebaran penggunaan tanah di wilayah Madiun. Klik pada area berwarna atau ikon pin untuk melihat detail penggunaan tanah, kelurahan, kecamatan, dan wilayah terkait.</p>
      </div>
      <div class="container">
        <div class="search-container mb-3">
          <div class="row g-2">
            <div class="col-md-4">
              <select id="search-wilayah" class="form-select">
                <option value="">Semua Wilayah</option>
              </select>
            </div>
            <div class="col-md-4">
              <select id="search-kecamatan" class="form-select">
                <option value="">Semua Kecamatan</option>
              </select>
            </div>
            <div class="col-md-4">
              <select id="search-kelurahan" class="form-select">
                <option value="">Semua Desa/Kelurahan</option>
              </select>
            </div>
          </div>
          <div class="d-flex justify-content-end mt-2">
            <button class="btn btn-sm btn-outline-success me-2" type="button" id="reset-search">
              <i class="bi bi-arrow-counterclockwise"></i> Reset
            </button>
          </div>
        </div>
        
        <div class="row">
          <div class="col-12">
            <div id="map" class="map-container mt-1 p-1 p-lg-3 bg-light rounded shadow-sm"></div>
          </div>
          
          <div class="col-12 mt-3 mt-lg-0">
            <div id="legend" class="legend-container p-2 p-lg-3 bg-light rounded shadow-sm">
              <h5 class="legend-title">Penggunaan Lahan Tanah</h5>
              <ul id="legend-list" class="list-unstyled mb-0"></ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Kontak Kami</h2>
        <p>Informasi lebih lengkap dan detail bisa langsung diakses melalui website utama kami.</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
          <div class="col-lg-8 text-center">
            <h4>Kunjungi Website Resmi Kami:</h4>
            <a href="https://kjppthy.com/" target="_blank" class="btn btn-primary btn-lg mt-3" style="background-color: #228b22; border-color: #228b22;">
              <i class="bi bi-box-arrow-up-right"></i> Website KJPP THY
            </a>
          </div>
        </div>
      </div>
    </section><!-- /Contact Section -->
</main>

<!-- Add Leaflet Search JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-search/3.0.9/leaflet-search.min.js"></script>
<!-- Add Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
      // Initialize the map
      var map = L.map('map').setView([-7.625, 111.523], 13);

      // Add base layers
      var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);

      var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
          attribution: '&copy; Esri, Maxar, Earthstar Geographics, and the GIS User Community'
      });

      // Layer control
      var baseMaps = {
          "Peta Standar": osmLayer,
          "Peta Satelit": satelliteLayer
      };
      L.control.layers(baseMaps).addTo(map);

      // Function to generate random color with restrictions
      let usedHues = new Set();
      const EXCLUDED_HUES = [
          30, 60, 90, 120, 150, // Hindari kuning-hijau
          180, 210, 240, 270, 300, 330 // Hindari warna lain yang tidak diinginkan
      ];
      const MIN_HUE_DIFFERENCE = 30; // Minimal perbedaan hue 30 derajat

      function getRandomColor() {
          let hue;
          let attempts = 0;
          const MAX_ATTEMPTS = 100;
          
          do {
              hue = Math.floor(Math.random() * 360);
              attempts++;
              
              // Cek apakah hue memenuhi syarat
              const isValid = !EXCLUDED_HUES.some(excluded => 
                  Math.abs(hue - excluded) < MIN_HUE_DIFFERENCE
              ) && Array.from(usedHues).every(used => 
                  Math.abs(hue - used) >= MIN_HUE_DIFFERENCE
              );
              
              if (isValid || attempts >= MAX_ATTEMPTS) break;
              
          } while (true);
          
          usedHues.add(hue);
          return `hsl(${hue}, 80%, 60%)`; // Lebih cerah dan kontras
      }

      // Fungsi untuk menghitung luas polygon dalam hektar
      function calculatePolygonArea(coords) {
          let total = 0;
          const outerRing = coords[0];
          const earthRadius = 6378137; // Radius bumi dalam meter
          
          for (let i = 0; i < outerRing.length; i++) {
              const [lon1, lat1] = outerRing[i];
              const [lon2, lat2] = outerRing[(i + 1) % outerRing.length];
              
              // Koreksi untuk latitude
              const avgLat = (lat1 + lat2) / 2 * Math.PI / 180;
              const xFactor = Math.cos(avgLat);
              
              total += (lon1 * xFactor * lat2) - (lon2 * xFactor * lat1);
          }
          
          const areaM2 = Math.abs(total) / 2 * (earthRadius * earthRadius);
          const areaHektar = areaM2 / 10000;
          
          return {
              m2: areaM2,
              ha: areaHektar
          };
      }

      // Fungsi untuk menghitung titik tengah polygon
      function  getPolygonCenter(coords) {
          // Handle both Polygon and MultiPolygon
          let points = [];
          if (coords[0][0] instanceof Array) { // MultiPolygon
              coords.forEach(polygon => {
                  points = points.concat(polygon[0]); // Take outer ring of each polygon
              });
          } else { // Simple Polygon
              points = coords[0]; // Take outer ring
          }
          
          let latSum = 0, lngSum = 0;
          for (let i = 0; i < points.length; i++) {
              lngSum += points[i][0];
              latSum += points[i][1];
          }
          return {
              lat: latSum / points.length,
              lng: lngSum / points.length
          };
      }

      // Process GeoJSON data
      var geoJsonData = <?= $geoJsonData ?>;
      var markerData = <?= $markerData ?>;
      var penggunaanColors = {};
      var allLayers = {};
      var areaData = {}; // Untuk menyimpan data luas per kawasan
      var districtData = {}; // Untuk menyimpan data per kecamatan
      
      // Layer untuk polygon
      var tanahLayer = L.geoJSON(null, {
          style: function(feature) {
              var kawasan = feature.properties.kawasan;
              return {
                  fillColor: penggunaanColors[kawasan],
                  weight: 2,
                  opacity: 1,
                  color: '#ffffff',
                  fillOpacity: 0.9,
                  dashArray: '0'
              };
          },
          onEachFeature: function(feature, layer) {
              var kawasan = feature.properties.kawasan;
              var kecamatan = feature.properties.kecamatan;
              
              // Hitung luas dari geometri
              var area = { m2: 0, ha: 0 };
              if (feature.geometry.type === 'Polygon') {
                  area = calculatePolygonArea(feature.geometry.coordinates);
              } else if (feature.geometry.type === 'MultiPolygon') {
                  feature.geometry.coordinates.forEach(polygon => {
                      const subArea = calculatePolygonArea(polygon);
                      area.m2 += subArea.m2;
                      area.ha += subArea.ha;
                  });
              }
              
              // Hitung titik tengah polygon
              var center = getPolygonCenter(feature.geometry.coordinates);
              feature.properties.center = center; // Simpan titik tengah di properti
              
              // Format angka dengan separator ribuan
              function formatNumber(num) {
                  return new Intl.NumberFormat('id-ID').format(num.toFixed(2));
              }
              
              // Simpan area di feature untuk digunakan di chart
              feature.properties.area = area.ha; // Untuk chart tetap pakai hektar
              
              if (!areaData[kawasan]) {
                  areaData[kawasan] = 0;
              }
              areaData[kawasan] += area.ha;
              
              if (!districtData[kecamatan]) {
                  districtData[kecamatan] = 0;
              }
              districtData[kecamatan] += area.ha;
              
              if (!allLayers[kawasan]) {
                  allLayers[kawasan] = {
                      polygons: [],
                      markers: []
                  };
              }
              allLayers[kawasan].polygons.push(layer);
              
              layer.bindPopup(
                  `<b>Penggunaan Tanah:</b> ${kawasan}<br>
                  <b>Kelurahan:</b> ${feature.properties.kelurahan}<br>
                  <b>Kecamatan:</b> ${feature.properties.kecamatan}<br>
                  <b>Wilayah:</b> ${feature.properties.wilayah}<br>
                  <b>Luas:</b> ${formatNumber(area.ha)} Ha<br>
                  <b>Luas:</b> ${formatNumber(area.m2)} m²<br>
                  <div style="display:inline-block;width:12px;height:12px;background-color:${penggunaanColors[kawasan]};border:1px solid #000;"></div>`
              );
          }
      });

      // Layer untuk marker dengan ikon pin custom
      var markerLayer = L.geoJSON(null, {
          pointToLayer: function(feature, latlng) {
              var kawasan = feature.properties.kawasan;
              
              // Buat custom icon dengan warna sesuai kawasan
              var customIcon = L.divIcon({
                  className: 'custom-pin',
                  html: `
                      <div style="position: relative; width: 32px; height: 32px;">
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="${penggunaanColors[kawasan]}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <path d="M12 21s8-4.5 8-10A8 8 0 0 0 4 11c0 5.5 8 10 8 10z" />
                              <circle cx="12" cy="11" r="3" fill="white"/>
                          </svg>
                      </div>
                  `,
                  iconSize: [32, 32],
                  iconAnchor: [16, 32],
                  popupAnchor: [0, -32]
              });
              
              // Gunakan titik tengah polygon jika ada, jika tidak gunakan koordinat marker
              var point = feature.properties.center ? 
                  [feature.properties.center.lat, feature.properties.center.lng] : 
                  [feature.geometry.coordinates[1], feature.geometry.coordinates[0]];
              
              return L.marker(point, { 
                  icon: customIcon,
                  kawasan: kawasan // Simpan info kawasan di marker
              });
          },
          onEachFeature: function(feature, layer) {
              var kawasan = feature.properties.kawasan;
              
              if (!allLayers[kawasan]) {
                  allLayers[kawasan] = {
                      polygons: [],
                      markers: []
                  };
              }
              allLayers[kawasan].markers.push(layer);
              
              layer.bindPopup(
                  `<b>Penggunaan Tanah:</b> ${kawasan}<br>
                  <b>Kelurahan:</b> ${feature.properties.kelurahan}<br>
                  <b>Kecamatan:</b> ${feature.properties.kecamatan}<br>
                  <b>Wilayah:</b> ${feature.properties.wilayah}<br>
                  <div style="display:inline-block;width:12px;height:12px;background-color:${penggunaanColors[kawasan]};border:1px solid #000;"></div>`
              );
          }
      });

      // Prepare colors untuk semua kawasan
      function prepareColors() {
          // Gabungkan data marker dan polygon
          var combinedFeatures = geoJsonData.features.concat(markerData.features);
          
          combinedFeatures.forEach(function(feature) {
              var kawasan = feature.properties.kawasan;
              if (!penggunaanColors[kawasan]) {
                  penggunaanColors[kawasan] = getRandomColor();
              }
          });
      }

      // Add data to layers
      function addDataToLayers() {
          prepareColors();
          
          tanahLayer.addData(geoJsonData);
          markerLayer.addData(markerData);
          
          // Atur zoom level untuk menentukan layer yang ditampilkan
          function updateLayers() {
              var zoom = map.getZoom();
              
              if (zoom < 18) {
                  // Tampilkan marker saat zoom out
                  if (map.hasLayer(tanahLayer)) {
                      map.removeLayer(tanahLayer);
                  }
                  if (!map.hasLayer(markerLayer)) {
                      map.addLayer(markerLayer);
                  }
              } else {
                  // Tampilkan polygon saat zoom in
                  if (map.hasLayer(markerLayer)) {
                      map.removeLayer(markerLayer);
                  }
                  if (!map.hasLayer(tanahLayer)) {
                      map.addLayer(tanahLayer);
                  }
              }
          }
          
          // Inisialisasi layer
          updateLayers();
          
          // Update layer saat zoom berubah
          map.on('zoomend', updateLayers);
          
          // Inisialisasi search control
          initSearchControl();
          
          // Update statistik
          updateStatistics();
          
          // Buat chart
          createCharts();
      }
      
      // Initialize search control
      function initSearchControl() {
            // Prepare all unique values for filtering
            const wilayahList = new Set();
            const kecamatanList = new Set();
            const kelurahanList = new Set();
            const kawasanList = new Set();
            
            // Process all features to collect filter values
            geoJsonData.features.concat(markerData.features).forEach(feature => {
                const props = feature.properties;
                if (props.wilayah) wilayahList.add(props.wilayah);
                if (props.kecamatan) kecamatanList.add(props.kecamatan);
                if (props.kelurahan) kelurahanList.add(props.kelurahan);
                if (props.kawasan) kawasanList.add(props.kawasan);
            });
            
            // Populate filter dropdowns
            populateDropdown('search-wilayah', Array.from(wilayahList).sort());
            populateDropdown('search-kecamatan', Array.from(kecamatanList).sort());
            populateDropdown('search-kelurahan', Array.from(kelurahanList).sort());
            
            // Set up filter change events
            document.getElementById('search-wilayah').addEventListener('change', function() {
                updateKecamatanOptions();
                applyFilters();
            });
            
            document.getElementById('search-kecamatan').addEventListener('change', function() {
                updateKelurahanOptions();
                applyFilters();
            });
            
            document.getElementById('search-kelurahan').addEventListener('change', applyFilters);
            
            // Set up reset button
            document.getElementById('reset-search').addEventListener('click', function() {
                document.getElementById('search-wilayah').value = '';
                document.getElementById('search-kecamatan').value = '';
                document.getElementById('search-kelurahan').value = '';
                resetFilter();
            });
        }

        function populateDropdown(id, items) {
            const dropdown = document.getElementById(id);
            if (!dropdown) return;
            
            const currentValue = dropdown.value;
            const defaultOptions = {
                'search-wilayah': 'Semua Wilayah',
                'search-kecamatan': 'Semua Kecamatan',
                'search-kelurahan': 'Semua Desa/Kelurahan'
            };
            
            dropdown.innerHTML = `<option value="">${defaultOptions[id] || 'Semua'}</option>`;
            
            if (items && items.length > 0) {
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item;
                    option.textContent = item;
                    dropdown.appendChild(option);
                });
                
                if (items.includes(currentValue)) {
                    dropdown.value = currentValue;
                }
            }
        }

        function updateKecamatanOptions() {
            const wilayah = document.getElementById('search-wilayah').value;
            const filteredKecamatan = new Set();
            
            geoJsonData.features.concat(markerData.features).forEach(feature => {
                if (!wilayah || feature.properties.wilayah === wilayah) {
                    if (feature.properties.kecamatan) {
                        filteredKecamatan.add(feature.properties.kecamatan);
                    }
                }
            });
            
            populateDropdown('search-kecamatan', Array.from(filteredKecamatan).sort());
            populateDropdown('search-kelurahan', []); // Reset kelurahan dropdown
        }

        function updateKelurahanOptions() {
            const wilayah = document.getElementById('search-wilayah').value;
            const kecamatan = document.getElementById('search-kecamatan').value;
            const filteredKelurahan = new Set();
            
            geoJsonData.features.concat(markerData.features).forEach(feature => {
                if ((!wilayah || feature.properties.wilayah === wilayah) &&
                    (!kecamatan || feature.properties.kecamatan === kecamatan)) {
                    if (feature.properties.kelurahan) {
                        filteredKelurahan.add(feature.properties.kelurahan);
                    }
                }
            });
            
            populateDropdown('search-kelurahan', Array.from(filteredKelurahan).sort());
        }

        // Apply filters to the map
        function applyFilters() {
            const wilayah = document.getElementById('search-wilayah').value;
            const kecamatan = document.getElementById('search-kecamatan').value;
            const kelurahan = document.getElementById('search-kelurahan').value;
            
            let anyMatch = false;
            const bounds = L.latLngBounds();
            
            tanahLayer.eachLayer(layer => {
                const props = layer.feature.properties;
                const match = 
                    (!wilayah || props.wilayah === wilayah) &&
                    (!kecamatan || props.kecamatan === kecamatan) &&
                    (!kelurahan || props.kelurahan === kelurahan);
                
                layer.setStyle({
                    fillOpacity: match ? 0.9 : 0,
                    weight: match ? 2 : 0,
                    color: match ? '#fff' : 'transparent',
                    fillColor: match ? penggunaanColors[props.kawasan] : 'transparent'
                });
                
                if (match) {
                    anyMatch = true;
                    if (layer.getBounds) {
                        bounds.extend(layer.getBounds());
                    }
                }
            });
            
            markerLayer.eachLayer(layer => {
                const props = layer.feature.properties;
                const match = 
                    (!wilayah || props.wilayah === wilayah) &&
                    (!kecamatan || props.kecamatan === kecamatan) &&
                    (!kelurahan || props.kelurahan === kelurahan);
                
                layer.setOpacity(match ? 1 : 0);
                
                if (match) {
                    anyMatch = true;
                    bounds.extend(layer.getLatLng());
                }
            });
            
            if (anyMatch && bounds.isValid()) {
                map.fitBounds(bounds, { 
                    padding: [50, 50],
                    maxZoom: 15 
                });
            }
        }
      
      // Update statistics
      function updateStatistics() {
          // Format angka dengan separator ribuan
          function formatNumber(num) {
              return new Intl.NumberFormat('id-ID').format(num.toFixed(2));
          }
          
          // Hitung total area
          var totalArea = Object.values(areaData).reduce((a, b) => a + b, 0);
          document.getElementById('total-area').textContent = formatNumber(totalArea) + ' Ha';
          
          // Temukan kawasan terluas
          var largestArea = 0;
          var largestAreaName = '-';
          for (var kawasan in areaData) {
              if (areaData[kawasan] > largestArea) {
                  largestArea = areaData[kawasan];
                  largestAreaName = kawasan;
              }
          }
          document.getElementById('largest-area').textContent = largestAreaName;
          
          // Hitung jumlah jenis penggunaan tanah
          var totalTypes = Object.keys(penggunaanColors).length;
          document.getElementById('total-types').textContent = totalTypes;
      }
      
      // Create charts
      function createCharts() {
          // Common responsive options for both charts
          const commonOptions = {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                  legend: {
                      position: 'right',
                      labels: {
                          boxWidth: 12,
                          padding: 10,
                          font: {
                              size: function(context) {
                                  // Adjust font size based on screen width
                                  const width = window.innerWidth;
                                  if (width < 576) return 10;
                                  if (width < 768) return 11;
                                  if (width < 992) return 12;
                                  return 13;
                              }
                          }
                      }
                  },
                  tooltip: {
                      enabled: true,
                      bodyFont: {
                          size: function() {
                              // Adjust tooltip font size based on screen width
                              const width = window.innerWidth;
                              if (width < 576) return 10;
                              if (width < 768) return 11;
                              return 12;
                          }
                      },
                      titleFont: {
                          size: function() {
                              const width = window.innerWidth;
                              if (width < 576) return 11;
                              if (width < 768) return 12;
                              return 13;
                          }
                      }
                  }
              }
          };

          // Pie chart untuk penggunaan lahan
          var ctxPie = document.getElementById('landUseChart').getContext('2d');
          var pieChart = new Chart(ctxPie, {
              type: 'pie',
              data: {
                  labels: Object.keys(areaData),
                  datasets: [{
                      data: Object.values(areaData),
                      backgroundColor: Object.keys(areaData).map(k => penggunaanColors[k]),
                      borderWidth: 1
                  }]
              },
              options: {
                  ...commonOptions,
                  plugins: {
                      ...commonOptions.plugins,
                      tooltip: {
                          ...commonOptions.plugins.tooltip,
                          callbacks: {
                              label: function(context) {
                                  var label = context.label || '';
                                  var value = context.raw || 0;
                                  var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                  var percentage = Math.round((value / total) * 100);
                                  return `${label}: ${value.toFixed(2)} Ha (${percentage}%)`;
                              }
                          }
                      }
                  },
                  onClick: function(evt, elements) {
                      if (elements.length > 0) {
                          var index = elements[0].index;
                          var kawasan = this.data.labels[index];
                          filterByKawasan(kawasan);
                      }
                  }
              }
          });
          
          // Bar chart untuk luas per kecamatan
          var ctxBar = document.getElementById('areaByDistrictChart').getContext('2d');
          var barChart = new Chart(ctxBar, {
              type: 'bar',
              data: {
                  labels: Object.keys(districtData),
                  datasets: [{
                      label: 'Luas Area (Ha)',
                      data: Object.values(districtData),
                      backgroundColor: 'rgba(34, 139, 34, 0.7)',
                      borderColor: 'rgba(34, 139, 34, 1)',
                      borderWidth: 1
                  }]
              },
              options: {
                  ...commonOptions,
                  plugins: {
                      ...commonOptions.plugins,
                      legend: {
                          display: false
                      }
                  },
                  scales: {
                      y: {
                          beginAtZero: true,
                          title: {
                              display: true,
                              text: 'Luas (Ha)',
                              font: {
                                  size: function() {
                                      const width = window.innerWidth;
                                      if (width < 576) return 10;
                                      if (width < 768) return 11;
                                      return 12;
                                  }
                              }
                          },
                          ticks: {
                              font: {
                                  size: function() {
                                      const width = window.innerWidth;
                                      if (width < 576) return 9;
                                      if (width < 768) return 10;
                                      return 11;
                                  }
                              }
                          }
                      },
                      x: {
                          title: {
                              display: true,
                              text: 'Kecamatan',
                              font: {
                                  size: function() {
                                      const width = window.innerWidth;
                                      if (width < 576) return 10;
                                      if (width < 768) return 11;
                                      return 12;
                                  }
                              }
                          },
                          ticks: {
                              font: {
                                  size: function() {
                                      const width = window.innerWidth;
                                      if (width < 576) return 9;
                                      if (width < 768) return 10;
                                      return 11;
                                  }
                              },
                              callback: function(value) {
                                  // Shorten labels on small screens
                                  const label = this.getLabelForValue(value);
                                  const width = window.innerWidth;
                                  if (width < 576) return label.substring(0, 8) + (label.length > 8 ? '...' : '');
                                  if (width < 768) return label.substring(0, 12) + (label.length > 12 ? '...' : '');
                                  return label;
                              }
                          }
                      }
                  },
                  onClick: function(evt, elements) {
                      if (elements.length > 0) {
                          var index = elements[0].index;
                          var kecamatan = this.data.labels[index];
                          // Filter by kecamatan
                          document.getElementById('search-kecamatan').value = kecamatan;
                          applyFilters();
                      }
                  }
              }
          });

          // Handle window resize for charts
          function handleChartResize() {
              pieChart.resize();
              barChart.resize();
          }

          // Debounce resize events
          let resizeTimer;
          window.addEventListener('resize', function() {
              clearTimeout(resizeTimer);
              resizeTimer = setTimeout(function() {
                  handleChartResize();
                  pieChart.update();
                  barChart.update();
              }, 200);
          });
      }
      
      // Filter function yang diperbaiki
      function filterByKawasan(kawasan) {
          var filteredBounds = L.latLngBounds(); // untuk menyimpan bounding box fitur yg cocok

          tanahLayer.eachLayer(function(layer) {
              var prop = layer.feature.properties;
              var match = prop.kawasan === kawasan;

              layer.setStyle({
                  fillOpacity: match ? 0.9 : 0,
                  weight: match ? 2 : 0,
                  color: match ? '#fff' : 'transparent',
                  fillColor: match ? penggunaanColors[prop.kawasan] : 'transparent'
              });

              if (match) {
                  if (layer.getBounds) {
                      filteredBounds.extend(layer.getBounds());
                  }
              }
          });

          markerLayer.eachLayer(function(layer) {
              var match = layer.feature.properties.kawasan === kawasan;
              layer.setOpacity(match ? 1 : 0);
          });

          if (filteredBounds.isValid()) {
              map.fitBounds(filteredBounds, {
                  padding: [50, 50],
                  maxZoom: 17 // bisa diatur sesuai kebutuhan
              });
          }
      }
      
      // Reset function
      function resetFilter() {
          tanahLayer.eachLayer(function(layer) {
              var kawasan = layer.feature.properties.kawasan;
              layer.setStyle({
                  fillOpacity: 0.9,
                  weight: 2,
                  color: '#ffffff',
                  fillColor: penggunaanColors[kawasan]
              });
          });

          markerLayer.eachLayer(function(layer) {
              layer.setOpacity(1);
          });

          map.setView([-7.625, 111.523], 13); // kembali ke default
      }
      
      // Update legend
      function updateLegend() {
          var legendList = document.getElementById('legend-list');
          legendList.innerHTML = '';

          // Reset button
          var resetItem = document.createElement('li');
          resetItem.innerHTML = `<button 
              class="btn btn-sm mb-2 text-white" 
              style="background-color: #228b22; border-color: #228b22;" 
              onclick="resetFilter()"
            >Tampilkan Semua</button>`;
          legendList.appendChild(resetItem);

          Object.entries(penggunaanColors).forEach(([kawasan, color]) => {
              var item = document.createElement('li');
              item.style.cursor = 'pointer';
              item.style.marginBottom = '5px';

              item.innerHTML = `
                  <span style="display:inline-block;width:20px;height:20px;background-color:${color};margin-right:10px;border:1px solid #000;"></span>
                  <span style="cursor:pointer;" onclick="filterByKawasan('${kawasan}')">${kawasan}</span>
                  <button class="btn btn-sm btn-outline-secondary float-end" onclick="filterByKawasan('${kawasan}')">
                      <i class="bi bi-eye-fill"></i>
                  </button>
              `;

              legendList.appendChild(item);
          });
      }
      
      // Initialize
      usedHues.clear();
      addDataToLayers();
      updateLegend();
      
      // Handle responsive behavior
      function handleResize() {
          map.invalidateSize();
          if (map.getZoom() === undefined) {
              map.setView([-7.625, 111.523], 13);
          }
      }
      
      setTimeout(handleResize, 100);
      window.addEventListener('resize', handleResize);
      
      // Make functions global
      window.filterByKawasan = filterByKawasan;
      window.resetFilter = resetFilter;
      
      // Toggle button text for About section
      const aboutToggle = document.getElementById('aboutToggle');
      aboutToggle.addEventListener('click', function() {
          const isExpanded = this.getAttribute('aria-expanded') === 'true';
          this.querySelector('.show-text').classList.toggle('d-none', isExpanded);
          this.querySelector('.hide-text').classList.toggle('d-none', !isExpanded);
          
          // Rotate chevron icon
          const icon = this.querySelector('i');
          if (isExpanded) {
              icon.classList.remove('bi-chevron-up');
              icon.classList.add('bi-chevron-down');
          } else {
              icon.classList.remove('bi-chevron-down');
              icon.classList.add('bi-chevron-up');
          }
      });
  });
</script>


<?= $this->endSection('content'); ?>