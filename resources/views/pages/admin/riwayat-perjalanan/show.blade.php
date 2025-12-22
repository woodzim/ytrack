@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
@endsection

@section('content')
    <!-- Start::page-header -->
    <div class="page-header-section">
        <div>
            <h1>Alat: {{ $idAlat }}</h1>
            <p>Detail tracking data untuk alat ini</p>
        </div>
        <div>
            <a href="{{ route('riwayat-perjalanan.index') }}" class="back-button">
                <i class="ri-arrow-left-line"></i>Kembali
            </a>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::app-content -->
    @if($riwayat->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <!-- Map Section -->
                <div class="detail-card">
                    <div class="card-header">
                        <h5><i class="ri-map-line me-2"></i>Visualisasi Rute</h5>
                    </div>
                    <div class="card-body">
                        <div id="map-container"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Data Table Section -->
                <div class="detail-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5><i class="ri-table-line me-2"></i>Data Tracking ({{ $totalCount }})</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr style="background-color: #f8f9fa;">
                                        <th style="width: 40px;">No</th>
                                        <th>Waktu</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Ketinggian</th>
                                        <th>Satelit</th>
                                        <th>Kecepatan</th>
                                        <th style="width: 120px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayat as $item)
                                        <tr>
                                            <td><span class="badge-no">{{ $loop->iteration }}</span></td>
                                            <td>
                                                <i class="ri-time-line me-1"></i>{{ $item->created_at->translatedFormat('H:i:s') }}
                                            </td>
                                            <td>{{ $item->lat }}</td>
                                            <td>{{ $item->long }}</td>
                                            <td>
                                                @if($item->alt)
                                                    {{ $item->alt }} m
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->sat)
                                                    <span class="badge" style="background-color: rgb(152, 95, 253); color: white;">{{ $item->sat }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->speed)
                                                    <span style="color: #10b981; font-weight: 600;">{{ $item->speed }} km/h</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('riwayat-perjalanan.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="ri-edit-line"></i> Edit
                                                    </a>
                                                    <form action="{{ route('riwayat-perjalanan.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                                            <i class="ri-delete-bin-line"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($riwayat->hasPages())
                            <div class="mt-3 d-flex justify-content-center">
                                {{ $riwayat->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="ri-map-pin-2-line" style="font-size: 48px; color: #ddd; margin-bottom: 16px; display: block;"></i>
                <h5 class="text-muted mb-2">Tidak ada data untuk tanggal ini</h5>
                <a href="{{ route('riwayat-perjalanan.index') }}" class="btn btn-primary btn-sm">
                    <i class="ri-arrow-left-line me-2"></i>Kembali
                </a>
            </div>
        </div>
    @endif
    <!-- End::app-content -->
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if map container exists
            const mapContainer = document.getElementById('map-container');
            if (!mapContainer) return;

            // Initialize map
            const map = L.map('map-container', {
                maxZoom: 14,
                minZoom: 4
            });

            // CartoDB Positron layer
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '© OpenStreetMap contributors, © CARTO',
                maxZoom: 14
            }).addTo(map);

            // Get coordinates from table
            const coordinates = [];
            const rows = document.querySelectorAll('.table tbody tr');
            
            rows.forEach((row) => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 4) {
                    const lat = parseFloat(cells[2].textContent.trim());
                    const lng = parseFloat(cells[3].textContent.trim());
                    const waktu = cells[1].textContent.trim();
                    const noCell = cells[0].querySelector('.badge-no');
                    const index = noCell ? parseInt(noCell.textContent) : coordinates.length + 1;
                    
                    if (!isNaN(lat) && !isNaN(lng)) {
                        coordinates.push({
                            lat: lat,
                            lng: lng,
                            waktu: waktu,
                            index: index
                        });
                    }
                }
            });

            if (coordinates.length > 0) {
                // Create numbered icons
                function createNumberedIcon(number) {
                    return L.divIcon({
                        html: `<div style="
                            background-color: #667eea;
                            color: white;
                            border-radius: 50%;
                            width: 32px;
                            height: 32px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-weight: bold;
                            font-size: 14px;
                            border: 3px solid white;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                        ">${number}</div>`,
                        iconSize: [32, 32],
                        className: 'numbered-marker'
                    });
                }

                // Add markers
                coordinates.forEach((coord) => {
                    L.marker([coord.lat, coord.lng], {
                        icon: createNumberedIcon(coord.index)
                    })
                    .bindPopup(`
                        <div style="font-size: 12px;">
                            <strong>Lokasi #${coord.index}</strong><br>
                            <i class="ri-time-line"></i> ${coord.waktu}<br>
                            📍 ${coord.lat.toFixed(6)}, ${coord.lng.toFixed(6)}
                        </div>
                    `)
                    .addTo(map);
                });

                // Draw route
                const latlngs = coordinates.map(coord => [coord.lat, coord.lng]);
                L.polyline(latlngs, {
                    color: '#667eea',
                    weight: 3,
                    opacity: 0.8,
                    dashArray: '5, 5'
                }).addTo(map);

                // Fit bounds
                const bounds = L.latLngBounds(latlngs);
                map.fitBounds(bounds, { padding: [150, 150] });
            }
        });
    </script>
@endsection
