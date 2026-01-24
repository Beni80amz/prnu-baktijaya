<div>
    <!-- Page Header -->
    <section class="bg-gradient-to-br from-primary to-primary-dark py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Peta Masjid & Musholla</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">Lokasi tempat ibadah di bawah naungan PRNU Baktijaya</p>
        </div>
    </section>

    <!-- Search -->
    <section
        class="bg-white dark:bg-background-dark border-b border-primary/10 dark:border-white/10 sticky top-20 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="relative max-w-md mx-auto">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari masjid atau musholla..."
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-primary/10 dark:border-white/10 bg-gray-50 dark:bg-white/5 text-background-dark dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
        </div>
    </section>

    <!-- Map & List -->
    <section class="py-12 bg-background-light dark:bg-background-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Mosque List -->
                <div class="lg:col-span-1 order-2 lg:order-1">
                    <div
                        class="bg-white dark:bg-white/5 rounded-2xl shadow-sm border border-primary/5 dark:border-white/5 overflow-hidden">
                        <div class="px-6 py-4 border-b border-primary/10 dark:border-white/10">
                            <h3 class="font-bold text-lg text-background-dark dark:text-white">
                                Daftar Masjid (<span id="peta-masjid-count">{{ $mosques->count() }}</span>)
                            </h3>
                        </div>
                        <div id="peta-masjid-list"
                            class="max-h-[600px] overflow-y-auto divide-y divide-primary/5 dark:divide-white/5">
                            @forelse($mosques as $mosque)
                                <div class="p-4 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-2xl text-primary">mosque</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start">
                                                <h4 class="font-bold text-background-dark dark:text-white">
                                                    {{ $mosque->name }}
                                                </h4>
                                                <span
                                                    class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-green-100 text-green-700 border border-green-200">Terverifikasi</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-white/60 mt-1 line-clamp-2">
                                                {{ $mosque->address }}
                                            </p>
                                            @if($mosque->phone)
                                                <p class="text-xs text-primary mt-2">
                                                    <span class="material-symbols-outlined text-sm align-middle">phone</span>
                                                    {{ $mosque->phone }}
                                                </p>
                                            @endif
                                            @if($mosque->latitude && $mosque->longitude)
                                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $mosque->latitude }},{{ $mosque->longitude }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 text-xs text-primary hover:text-accent mt-2">
                                                    <span class="material-symbols-outlined text-sm">directions</span>
                                                    Petunjuk Arah
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500 dark:text-white/50">
                                    <span class="material-symbols-outlined text-4xl mb-2">location_off</span>
                                    <p>Tidak ada masjid terverifikasi ditemukan.</p>
                                    <p class="text-sm">Menunggu deteksi otomatis...</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Map Placeholder -->
                <div class="lg:col-span-2 order-1 lg:order-2">
                    <div
                        class="bg-white dark:bg-white/5 rounded-2xl shadow-sm border border-primary/5 dark:border-white/5 overflow-hidden h-[400px] lg:h-[600px] relative z-0">
                        <div id="peta-masjid-map" class="w-full h-full z-10" wire:ignore></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:navigated', function () {
            initPetaMasjidMap();
        });

        // Fallback for initial load
        document.addEventListener('DOMContentLoaded', function () {
            initPetaMasjidMap();
        });

        function initPetaMasjidMap() {
            var container = document.getElementById('peta-masjid-map');
            if (!container) return;

            // Cleanup existing map instance
            if (container._leaflet_id) {
                container._leaflet_id = null;
            }
            // Clear potential duplicate children
            if (container.hasChildNodes()) {
                container.innerHTML = '';
            }

            console.log('Initializing Peta Masjid Map...');

            try {
                // Default Center (Baktijaya)
                var defaultCenter = [-6.3827433, 106.8525385];
                var map = L.map('peta-masjid-map').setView(defaultCenter, 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var bounds = [];
                var addedCoords = new Set();

                // Add Verified Mosques
                var verifiedMosques = @json($mosques);
                var settingsAddress = @json($settings['contact_address'] ?? 'Kelurahan Baktijaya, Depok');

                console.log('Verified Mosques:', verifiedMosques.length);

                verifiedMosques.forEach(function (mosque) {
                    if (mosque.latitude && mosque.longitude) {
                        var lat = parseFloat(mosque.latitude);
                        var lng = parseFloat(mosque.longitude);

                        addPetaMarker(lat, lng, mosque.name, mosque.address, 'Masjid', true);
                        bounds.push([lat, lng]);
                        addedCoords.add(lat.toFixed(5) + ',' + lng.toFixed(5));
                    }
                });

                // Adjust bounds if we have verified mosques
                if (bounds.length > 0) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }

                // --- Helper Functions ---

                function addPetaMarker(lat, lng, name, address, type, isVerified) {
                    var marker = L.marker([lat, lng]).addTo(map);

                    var badgeClass = isVerified
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-100 text-gray-700 border border-gray-300';

                    var badgeText = isVerified
                        ? 'Terverifikasi'
                        : 'Terdeteksi Otomatis';

                    var popupContent = '<div class="p-2 min-w-[200px]">' +
                        '<h4 class="font-bold text-gray-900 mb-1">' + name + '</h4>' +
                        '<p class="text-xs text-gray-600 mb-2">' + (address || 'Alamat tidak tersedia') + '</p>' +
                        '<span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded ' + badgeClass + '">' + badgeText + '</span>' +
                        '<a href="https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng + '" target="_blank" class="block mt-2 text-xs text-blue-600 font-bold hover:underline">Petunjuk Arah</a>' +
                        '</div>';

                    marker.bindPopup(popupContent);
                }

                function addPetaListItem(lat, lng, name, address) {
                    var listContainer = document.getElementById('peta-masjid-list');
                    var countSpan = document.getElementById('peta-masjid-count');

                    if (listContainer) {
                        // Remove "No Data" placeholder if exists
                        var emptyState = listContainer.querySelector('.text-center.p-8');
                        if (emptyState) emptyState.remove();

                        var item = document.createElement('div');
                        item.className = 'p-4 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors animate-fade-in border-b border-primary/5 dark:border-white/5';
                        item.innerHTML = `
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0 text-gray-400">
                                    <span class="material-symbols-outlined text-2xl">location_on</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h4 class="font-bold text-background-dark dark:text-white line-clamp-1">${name}</h4>
                                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-gray-100 text-gray-600 border border-gray-200">Otomatis</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-white/60 mt-1 line-clamp-2">${address || 'Alamat sekitar area ini'}</p>

                                    <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" 
                                        target="_blank"
                                        class="inline-flex items-center gap-1 text-xs text-primary hover:text-accent mt-2 font-bold uppercase tracking-wide">
                                        <span class="material-symbols-outlined text-sm">directions</span>
                                        Petunjuk Arah
                                    </a>
                                </div>
                            </div>
                        `;
                        listContainer.appendChild(item);

                        if (countSpan) {
                            var currentText = countSpan.innerText;
                            var current = parseInt(currentText.replace(/[^0-9]/g, '')) || 0;
                            countSpan.innerText = current + 1;
                        }
                    }
                }

                // --- Auto Discovery Logic ---
                var searchCenter = defaultCenter;

                function runOverpassDiscovery(lat, lng) {
                    // Show scanning area
                    L.circle([lat, lng], {
                        color: 'var(--color-primary)',
                        fillColor: 'var(--color-primary)',
                        fillOpacity: 0.1,
                        radius: 1000
                    }).addTo(map);

                    var radius = 1000;
                    var query = `
                        [out:json][timeout:25];
                        (
                          node["amenity"="place_of_worship"]["religion"="muslim"](around:${radius},${lat},${lng});
                          way["amenity"="place_of_worship"]["religion"="muslim"](around:${radius},${lat},${lng});
                        );
                        out center; 
                    `;

                    console.log('Fetching Overpass data...');
                    fetch('https://overpass-api.de/api/interpreter', {
                        method: 'POST',
                        body: query
                    })
                        .then(response => response.json())
                        .then(osmData => {
                            if (osmData.elements) {
                                var newCount = 0;
                                osmData.elements.forEach(el => {
                                    var lat = el.lat || el.center.lat;
                                    var lon = el.lon || el.center.lon;
                                    var name = el.tags.name || 'Masjid/Musholla (Tanpa Nama)';

                                    var key = lat.toFixed(5) + ',' + lon.toFixed(5);
                                    var isDuplicate = false;

                                    // Check against existing coordinates (both verified and already added detected)
                                    addedCoords.forEach(coord => {
                                        var parts = coord.split(',');
                                        var cLat = parseFloat(parts[0]);
                                        var cLon = parseFloat(parts[1]);
                                        var dLat = Math.abs(cLat - lat);
                                        var dLon = Math.abs(cLon - lon);
                                        // Simple proximity check ~20m
                                        if (dLat < 0.0002 && dLon < 0.0002) isDuplicate = true;
                                    });

                                    if (!isDuplicate) {
                                        addPetaMarker(lat, lon, name, '', 'Masjid', false);
                                        addPetaListItem(lat, lon, name, '');
                                        addedCoords.add(key);
                                        bounds.push([lat, lon]);
                                        newCount++;
                                    }
                                });

                                console.log('Auto-detected mosques added:', newCount);

                                if (bounds.length > 0) {
                                    map.fitBounds(bounds, { padding: [50, 50] });
                                }
                            }
                        })
                        .catch(e => console.error("Overpass Peta Error:", e));
                }

                if (settingsAddress) {
                    console.log('Geocoding address:', settingsAddress);
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(settingsAddress)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                var centerLat = parseFloat(data[0].lat);
                                var centerLng = parseFloat(data[0].lon);

                                // If no verified mosques, center on address
                                if (bounds.length === 0) {
                                    map.setView([centerLat, centerLng], 15);
                                }

                                runOverpassDiscovery(centerLat, centerLng);
                            } else {
                                console.warn('Geocoding failed, using default center');
                                runOverpassDiscovery(searchCenter[0], searchCenter[1]);
                            }
                        })
                        .catch(e => {
                            console.error('Geocoding error:', e);
                            runOverpassDiscovery(searchCenter[0], searchCenter[1]); // Fallback
                        });
                } else {
                    runOverpassDiscovery(searchCenter[0], searchCenter[1]);
                }

            } catch (error) {
                console.error('Map Init Error:', error);
            }
        }
    </script>
@endpush