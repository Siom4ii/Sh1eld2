@extends('layouts.skydash-v')
@section('title', 'Dashboard')
@section('heading', 'MBLRC — Overview')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<style>#frMap{height:420px;width:100%;border-radius:8px}</style>
@endpush

@section('content')
<div class="row">
    @foreach ([
        ['Registered FRs', $stats['registered'], 'bg-gradient-primary', 'mdi-account-multiple'],
        ['Active', $stats['active'], 'bg-gradient-success', 'mdi-account-check'],
        ['Reintegrated', $stats['reintegrated'], 'bg-gradient-info', 'mdi-hand-heart'],
        ['Completed', $stats['completed'], 'bg-gradient-dark', 'mdi-school'],
        ['On-going', $stats['ongoing'], 'bg-gradient-warning', 'mdi-progress-clock'],
        ['Not-Started', $stats['not_started'], 'bg-gradient-secondary', 'mdi-pause-circle'],
    ] as [$label, $value, $bg, $icon])
        <div class="col-md-4 col-xl-2 grid-margin stretch-card">
            <div class="card {{ $bg }} text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 font-weight-bold">{{ $value }}</h3>
                            <p class="mb-0 text-white-50">{{ $label }}</p>
                        </div>
                        <i class="mdi {{ $icon }} icon-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Program Status Analytics</h4>
                <p class="text-muted mb-3">Reintegration progress, last 7 months</p>
                <canvas id="programChart" height="140"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Overall Statistics</h4>
                <p class="text-muted mb-3">Registered vs reintegrated, last 7 months</p>
                <canvas id="overallChart" height="140"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Former Rebel Locations</h4>
                <div id="frMap" data-locations="{{ route('mblrc.fr.locations') }}"></div>
            </div>
        </div>
    </div>
</div>

<div id="mblrcData" data-analytics="{{ route('mblrc.analytics') }}" hidden></div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
(function(){
    const ds = (label,data,color)=>({label,data,borderColor:color,backgroundColor:color+'22',tension:.35,fill:true,pointRadius:3});
    fetch(document.getElementById('mblrcData').dataset.analytics).then(r=>r.json()).then(d=>{
        new Chart(document.getElementById('programChart'),{type:'line',data:{labels:d.labels,datasets:[
            ds('Not-Started',d.program.not_started,'#98a2b3'),ds('On-going',d.program.ongoing,'#f79009'),ds('Completed',d.program.completed,'#039855')]},
            options:{responsive:true,plugins:{legend:{position:'bottom'}},scales:{y:{beginAtZero:true,ticks:{precision:0}}}}});
        new Chart(document.getElementById('overallChart'),{type:'line',data:{labels:d.labels,datasets:[
            ds('Registered',d.overall.registered,'#2c4199'),ds('Reintegrated',d.overall.reintegrated,'#12b76a')]},
            options:{responsive:true,plugins:{legend:{position:'bottom'}},scales:{y:{beginAtZero:true,ticks:{precision:0}}}}});
    });
    const map=L.map('frMap').setView([6.7497,125.3572],10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(map);
    const cluster=L.markerClusterGroup(); map.addLayer(cluster);
    fetch(document.getElementById('frMap').dataset.locations).then(r=>r.json()).then(rows=>{
        const b=[]; rows.forEach(fr=>{ L.marker([fr.lat,fr.lng]).bindPopup('<strong>'+fr.name+'</strong><br>'+fr.status+'<br><a href="'+fr.url+'">View profile</a>').addTo(cluster); b.push([fr.lat,fr.lng]); });
        if(b.length) map.fitBounds(b,{padding:[30,30]});
    });
})();
</script>
@endpush
