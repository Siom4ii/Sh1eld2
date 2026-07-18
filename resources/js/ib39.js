import L from 'leaflet';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const DAVAO_SUR = [6.7497, 125.3572];

const STATUS_COLORS = {
    Active: '#22c55e',
    Reintegrated: '#3a56b8',
    Inactive: '#94a3b8',
    'Under Review': '#fbbf24',
    Completed: '#12b76a',
    'On hold': '#f59e0b',
};

export function initIb39Dashboard() {
    const el = document.getElementById('ib39Dashboard');
    if (!el) return;
    const data = JSON.parse(el.dataset.status || '{}');
    const labels = ['Konsolidado', 'Rekonsilida', 'Expansion', 'Recovery'];
    const colors = ['#ef4444', '#fb923c', '#facc15', '#22c55e'];
    const ctx = document.getElementById('statusChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{ data: labels.map((l) => data[l] || 0), backgroundColor: colors, borderWidth: 0 }],
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } },
    });
}

export function initIb39Map() {
    const el = document.getElementById('ib39Map');
    if (!el) return;
    const map = L.map(el).setView(DAVAO_SUR, 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap', maxZoom: 19,
    }).addTo(map);

    fetch(el.dataset.source, { headers: { Accept: 'application/json' } })
        .then((r) => r.json())
        .then((rows) => {
            const bounds = [];
            rows.forEach((fr) => {
                const color = STATUS_COLORS[fr.status] || '#64748b';
                L.circleMarker([fr.lat, fr.lng], {
                    radius: 7, color, fillColor: color, fillOpacity: 0.8, weight: 2,
                }).bindPopup(
                    `<strong>${fr.name}</strong><br>${fr.status} · ${fr.batch ?? ''}<br>${fr.address ?? ''}`
                ).addTo(map);
                bounds.push([fr.lat, fr.lng]);
            });
            if (bounds.length) map.fitBounds(bounds, { padding: [30, 30] });
        });
}
