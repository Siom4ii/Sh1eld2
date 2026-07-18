@extends('layouts.skydash-v')
@section('title', 'Add Area')
@section('heading', 'RCSP Areas')

@php
    $statusClass = fn ($s) => 'status-'.strtolower($s ?: 'unclassified');
@endphp

@push('styles')
<style>
    .status-badge {
        padding: .4rem .9rem; border-radius: 9999px; font-size: .72rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: .05em; display: inline-flex; align-items: center; gap: .375rem;
        box-shadow: 0 2px 4px rgba(0,0,0,.05);
    }
    .status-konsolidado { background: linear-gradient(135deg,#fecaca,#fee2e2); color: #dc2626; border: 1px solid #fecaca; }
    .status-rekonsilida { background: linear-gradient(135deg,#fed7aa,#ffedd5); color: #ea580c; border: 1px solid #fed7aa; }
    .status-expansion   { background: linear-gradient(135deg,#fef08a,#fef9c3); color: #ca8a04; border: 1px solid #fef08a; }
    .status-recovery    { background: linear-gradient(135deg,#86efac,#dcfce7); color: #16a34a; border: 1px solid #86efac; }
    .status-unclassified { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h3 class="font-weight-bold mb-0">RCSP Barangays</h3>
                    <p class="text-muted mb-0">Set former-rebel counts to classify each barangay.</p>
                </div>
                <p class="text-muted small mb-0">
                    Thresholds: ≥20 Konsolidado · ≥15 Rekonsilida · ≥10 Expansion · &lt;10 Recovery
                </p>
            </div>

            {{-- Filters --}}
            <form method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-4">
                <div class="input-group" style="width:16rem;">
                    <span class="input-group-text bg-white"><i class="mdi mdi-magnify"></i></span>
                    <input name="search" value="{{ request('search') }}" placeholder="Search barangays, municipalities…" class="form-control">
                </div>
                <select name="municipality" class="form-select" style="width:12rem;" onchange="this.form.submit()">
                    <option value="">All Municipalities</option>
                    @foreach ($municipalities as $m)
                        <option value="{{ $m }}" @selected(request('municipality') === $m)>{{ $m }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select" style="width:11rem;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    @foreach (['Konsolidado', 'Rekonsilida', 'Expansion', 'Recovery'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-secondary"><i class="mdi mdi-filter-variant"></i> Filter</button>
                @if (request()->hasAny(['search', 'municipality', 'status']))
                    <a href="{{ route('ib39.areas.index') }}" class="btn btn-light"><i class="mdi mdi-close"></i> Clear</a>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Province</th>
                            <th>Municipality/City</th>
                            <th>Barangay</th>
                            <th>Status</th>
                            <th>FR's</th>
                            <th style="width:14rem">Set FR count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($areas as $area)
                            <tr>
                                <td>Davao del Sur</td>
                                <td>{{ $area->municipality }}</td>
                                <td class="font-weight-medium">{{ $area->barangay }}</td>
                                <td><span class="status-badge {{ $statusClass($area->status) }}">{{ $area->status ?: 'Unclassified' }}</span></td>
                                <td>{{ $area->frs }}</td>
                                <td>
                                    <form method="POST" action="{{ route('ib39.areas.update', $area) }}" class="d-flex align-items-center gap-2">
                                        @csrf @method('PUT')
                                        <input name="frs" type="number" min="0" value="{{ $area->frs }}" class="form-control" style="width:6rem;">
                                        <button class="btn btn-outline-secondary">Set</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-5">No RCSP Barangays found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $areas->links() }}</div>
        </div>
    </div>
@endsection
