@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <style>
        .page-header-section {
            background: rgb(152, 95, 253);
            border-radius: 8px;
            padding: 24px;
            color: white;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: start;
        }
        .page-header-section h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            color: white;
        }
        .page-header-section p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .back-button {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
        #map-container {
            height: 400px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            overflow: hidden;
        }
        .map-legend {
            background: white;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        .map-legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        .map-legend-item:last-child {
            margin-bottom: 0;
        }
        .map-legend-item i {
            margin-right: 8px;
            color: #667eea;
        }
        .detail-card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            margin-bottom: 24px;
        }
        .detail-card .card-header {
            background: #f0f3ff;
            border-bottom: 1px solid #e0e0e0;
            padding: 16px 20px;
        }
        .detail-card .card-header h5 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }
        .detail-card .card-body {
            padding: 24px;
        }
        .table {
            margin-bottom: 0;
        }
        .table tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .table thead th {
            border-bottom: 2px solid #e0e0e0 !important;
            padding: 12px 8px !important;
            font-size: 12px;
            font-weight: 600;
        }
        .table tbody td {
            padding: 10px 8px !important;
            vertical-align: middle;
            font-size: 12px;
        }
        .action-buttons {
            display: flex;
            gap: 6px;
        }
        .action-buttons .btn-sm {
            padding: 6px 10px;
            font-size: 11px;
        }
        .badge-no {
            background-color: #667eea;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            min-width: 24px;
            text-align: center;
            font-weight: 600;
        }

        /* Mobile Responsive */
        @media (max-width: 767px) {
            .page-header-section {
                flex-direction: column;
                padding: 12px;
                margin-bottom: 16px;
            }
            .page-header-section h1 {
                font-size: 18px;
                margin-bottom: 8px;
                color: white;
            }
            .detail-card .card-body {
                padding: 16px;
            }
            .table thead {
                display: none;
            }
            .table tbody tr {
                display: block;
                margin-bottom: 12px;
                border: 1px solid #e0e0e0;
                border-radius: 6px;
                overflow: hidden;
            }
            .table tbody td {
                display: block;
                padding: 8px 12px;
                border: none;
                text-align: right;
                position: relative;
                padding-left: 50%;
            }
            .table tbody td:before {
                content: attr(data-label);
                position: absolute;
                left: 12px;
                font-weight: 600;
                color: rgb(152, 95, 253);
                text-align: left;
                width: 40%;
            }
            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }
            .action-buttons .btn-sm {
                width: 100%;
            }
        }
    </style>
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
                                                        <button type="submit" class="btn btn-danger btn-sm w-100">
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
