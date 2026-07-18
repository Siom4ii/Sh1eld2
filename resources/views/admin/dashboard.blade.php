@extends('layouts.skydash-h')
@section('title', 'Katuparan Center')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .embed-container { position: relative; height: 480px; overflow: hidden; border-radius: 10px; }
    #adminMap { position: absolute; inset: 0; width: 100%; height: 100%; z-index: 0; }
    .map-overlay {
        position: absolute; inset: 0; width: 100%; height: 100%;
        background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 30%, rgba(0,0,0,0) 60%);
        z-index: 500; pointer-events: none; overflow: hidden;
    }
    .overlay-content { padding: 2rem; color: #fff; max-width: 620px; pointer-events: none; }
    .overlay-content h2 { font-size: 2.4rem; font-weight: 600; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
    .overlay-content .subtitle { font-size: 1.05rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.3); }
    .overlay-divider { width: 60px; height: 4px; background: linear-gradient(to right, #4CAF50, #2196F3); margin: 1rem 0; border-radius: 2px; }
    .statistics-container { margin-top: 1.5rem; display: flex; gap: 3rem; align-items: flex-start; }
    .stats-column { display: flex; flex-direction: column; gap: 1rem; }
    .stat-item { background: rgba(255,255,255,0.1); padding: 0.75rem 1.5rem; border-radius: 6px; text-align: left; min-width: 200px; backdrop-filter: blur(8px); }
    .stat-item.compact { width: fit-content; min-width: 150px; padding: 0.75rem 1.2rem; }
    .stat-number { display: block; font-size: 2rem; font-weight: bold; line-height: 1; margin-bottom: 0.25rem; }
    .stat-label { font-size: 0.85rem; }
    .stats-title { font-size: 1rem; margin: 0.5rem 0; }
    .municipality-grid { display: flex; gap: 1.5rem; }
    .municipality-column { display: flex; flex-direction: column; gap: 0.5rem; }
    .municipality-item { display: flex; justify-content: space-between; gap: 1rem; background: rgba(255,255,255,0.1); padding: 0.4rem 0.9rem; border-radius: 6px; min-width: 180px; }
    .municipality-name { font-size: 0.85rem; }
    .municipality-count { font-size: 0.85rem; background: rgba(255,255,255,0.2); padding: 0.1rem 0.5rem; border-radius: 3px; min-width: 1.5rem; text-align: center; }

    .rcsp-stats-container { padding: 2rem; background: #f8f9fa; margin-top: 1.5rem; border-radius: 10px; }
    .rcsp-flex-container { display: flex; gap: 2rem; }
    .rcsp-cards { flex: 1; display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    .rcsp-item { background: #fff; padding: 1.2rem; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 1rem; }
    .municipality-seal { width: 56px; height: 56px; object-fit: contain; }
    .rcsp-details h3 { margin: 0; font-size: 1.05rem; color: #333; }
    .rcsp-count { font-size: 1.8rem; font-weight: bold; color: #2196F3; margin: 0.25rem 0; }
    .rcsp-details p { margin: 0; font-size: 0.75rem; color: #666; }
    .rcsp-chart { flex: 1; background: #fff; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .bar-chart { height: 320px; }
    .chart-label { text-align: center; color: #333; margin-top: 0.5rem; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body p-0">
                <div class="embed-container">
                    <div id="adminMap"></div>
                    <div class="map-overlay">
                        <div class="overlay-content">
                            <h2>Welcome to SHIELD Program</h2>
                            <p class="subtitle">Strengthening Institutions and Empowering Localities Against Discrimination Programs for Former Rebels</p>
                            <div class="overlay-divider"></div>
                            <div class="statistics-container">
                                <div class="stats-column">
                                    <div class="stat-item compact">
                                        <span class="stat-number">{{ $stats['former_rebels'] }}</span>
                                        <span class="stat-label">Total Former Rebels</span>
                                    </div>
                                    <h3 class="stats-title">RCSP Barangays per Municipality</h3>
                                    <div class="municipality-grid">
                                        @foreach ($municipalities->filter(fn ($m) => $m['total'] > 0)->chunk(ceil(max($municipalities->where('total','>',0)->count(),1)/2)) as $col)
                                            <div class="municipality-column">
                                                @foreach ($col as $m)
                                                    <div class="municipality-item">
                                                        <span class="municipality-name">{{ $m['name'] }}</span>
                                                        <span class="municipality-count">{{ $m['total'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="rcsp-stats-container">
    <div class="rcsp-flex-container">
        <div class="rcsp-cards">
            @foreach ($municipalities as $m)
                <div class="rcsp-item">
                    @if ($m['seal'])
                        <img src="{{ asset('assets/LGUS/'.$m['seal']) }}" alt="{{ $m['name'] }} Seal" class="municipality-seal">
                    @else
                        <div class="municipality-seal" style="background:#e2e8f0;border-radius:50%"></div>
                    @endif
                    <div class="rcsp-details">
                        <h3>{{ $m['name'] }}</h3>
                        <div class="rcsp-count">{{ $m['recognized'] }}</div>
                        <p>Total Recognized RCSP Barangay</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="rcsp-chart">
            <div class="bar-chart"><canvas id="rcspBarChart"></canvas></div>
            <div class="chart-label" style="font-size:1.2em;">RCSP Barangay per Municipality</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Operational map with FR locations.
    (function () {
        const map = L.map('adminMap', { zoomControl: false }).setView([6.7497, 125.3572], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        // Container is fixed-height + hidden overflow; recalc once laid out so tiles fill it.
        setTimeout(() => map.invalidateSize(), 200);
        const points = @json($frPoints);
        const colors = { Active:'#22c55e', Reintegrated:'#2c4199', Inactive:'#94a3b8', 'Under Review':'#f59e0b' };
        const bounds = [];
        points.forEach(p => {
            L.circleMarker([p.lat, p.lng], { radius:7, color: colors[p.status]||'#64748b', fillColor: colors[p.status]||'#64748b', fillOpacity:0.85, weight:2 })
                .bindPopup('<strong>'+p.name+'</strong><br>'+p.status+'<br>'+(p.address||''))
                .addTo(map);
            bounds.push([p.lat, p.lng]);
        });
        if (bounds.length) map.fitBounds(bounds, { padding:[40,40] });
    })();

    // RCSP barangays per municipality bar chart (real data).
    const labels = @json($municipalities->where('total','>',0)->pluck('name')->values());
    const totals = @json($municipalities->where('total','>',0)->pluck('total')->values());
    const recognized = @json($municipalities->where('total','>',0)->pluck('recognized')->values());
    new Chart(document.getElementById('rcspBarChart').getContext('2d'), {
        type: 'bar',
        data: { labels, datasets: [
            { label:'RCSP Barangays', data: totals, backgroundColor:'#4DCFE0' },
            { label:'Recognized', data: recognized, backgroundColor:'#7FE3E1' },
        ]},
        options: { responsive:true, maintainAspectRatio:false,
            scales:{ y:{ beginAtZero:true, ticks:{ stepSize:1 } } },
            plugins:{ legend:{ position:'bottom' } } }
    });
</script>
@endpush
