@extends('layouts.skydash-v')
@section('title', 'Map')
@section('heading', 'Operational Map')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div>
                            <h5 class="mb-1">Former Rebel Locations</h5>
                            <p class="text-muted small mb-0">Davao del Sur area of operations</p>
                        </div>
                        <div class="d-flex flex-wrap gap-3 small">
                            @foreach (['Active' => '#22c55e', 'Reintegrated' => '#2c4199', 'Inactive' => '#94a3b8', 'Under Review' => '#f59e0b'] as $st => $c)
                                <span class="d-flex align-items-center gap-1">
                                    <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background: {{ $c }}"></span>{{ $st }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div id="ib39Map" style="height:32rem;width:100%;" data-source="{{ route('ib39.map.data') }}"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        (function () {
            var el = document.getElementById('ib39Map');
            if (!el || typeof L === 'undefined') return;

            var DAVAO_SUR = [6.7497, 125.3572];
            var STATUS_COLORS = {
                Active: '#22c55e',
                Reintegrated: '#2c4199',
                Inactive: '#94a3b8',
                'Under Review': '#f59e0b',
                Completed: '#12b76a',
            };

            var map = L.map(el).setView(DAVAO_SUR, 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap',
                maxZoom: 19,
            }).addTo(map);

            fetch(el.dataset.source, { headers: { Accept: 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (rows) {
                    var bounds = [];
                    rows.forEach(function (fr) {
                        var color = STATUS_COLORS[fr.status] || '#64748b';
                        L.circleMarker([fr.lat, fr.lng], {
                            radius: 7, color: color, fillColor: color, fillOpacity: 0.8, weight: 2,
                        }).bindPopup(
                            '<strong>' + fr.name + '</strong><br>' + fr.status + ' · ' + (fr.batch ?? '') + '<br>' + (fr.address ?? '')
                        ).addTo(map);
                        bounds.push([fr.lat, fr.lng]);
                    });
                    if (bounds.length) map.fitBounds(bounds, { padding: [30, 30] });
                });
        })();
    </script>
@endpush
