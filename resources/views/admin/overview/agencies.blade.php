@extends('layouts.skydash-h')
@section('title', 'Agencies')
@section('heading', 'Bulk File Submission - IMPLAN')

@php
    $targetNames = fn ($im) => collect($im->target_areas ?? [])
        ->map(fn ($id) => $areaNames[$id] ?? "Area #$id")->implode(', ');
    $tabMeta = [
        'ongoing'  => ['Ongoing', 'bg-warning text-dark', 'badge-outline-warning'],
        'awaiting' => ['Awaiting Action', 'bg-danger', 'badge-outline-danger'],
        'verified' => ['Verified', 'bg-success', 'badge-outline-success'],
    ];
@endphp

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title mb-5" style="font-weight: bold; font-size: 1.5rem; text-align: left;">Bulk File Submission - IMPLAN</h1>

                    <ul class="nav nav-pills nav-pills-success" role="tablist" id="pills-tab" style="margin-bottom: 0; border-bottom: 0;">
                        @foreach ($tabMeta as $key => [$label, $countBadge, $rowBadge])
                            <li class="nav-item">
                                <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="pill" href="#tab-{{ $key }}" role="tab">
                                    {{ $label }} <span class="badge {{ $countBadge }}">{{ $tabs[$key]->count() }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        @foreach ($tabMeta as $key => [$label, $countBadge, $rowBadge])
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $key }}" role="tabpanel">
                                <div class="row mt-3">
                                    <div class="col-md-12 d-flex align-items-end justify-content-start">
                                        <div class="form-group mb-0 me-3" style="width: 200px">
                                            <select class="form-control form-control-sm"><option>Filter by: All</option></select>
                                        </div>
                                        <div class="form-group mb-0 me-3" style="width: 200px">
                                            <input type="date" class="form-control form-control-sm">
                                        </div>
                                        <div class="form-group mb-0 me-3" style="width: 200px">
                                            <input type="text" class="form-control form-control-sm" placeholder="Search...">
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Program</th>
                                                <th>Issue</th>
                                                <th>Target Area</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($tabs[$key] as $im)
                                                <tr>
                                                    <td>{{ $im->program ?: '—' }}</td>
                                                    <td>{{ $im->issues }}</td>
                                                    <td>{{ $targetNames($im) ?: '—' }}</td>
                                                    <td><span class="badge {{ $rowBadge }}">{{ $im->status }}</span></td>
                                                    <td>
                                                        <a href="{{ route('admin.implan.show', $im) }}" class="btn btn-primary btn-sm">Open</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center">No records found for "{{ $label }}" status.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .nav-pills { padding: 0; margin-bottom: 0 !important; position: relative; z-index: 1; }
    .nav-pills .nav-item { margin-right: 6px; }
    .nav-pills .nav-link {
        border: 1px solid #dee2e6; border-radius: 8px 8px 0 0; padding: 8px 25px;
        background: #f8f9fa; color: #666; transition: all 0.2s ease;
    }
    .nav-pills .nav-link.active {
        background: #fff !important; color: #000 !important;
        border-bottom-color: transparent; border-top: 5px solid #280274; padding-top: 6px;
    }
    .nav-pills .nav-item:hover .nav-link:not(.active) { background: #f0f0f0 !important; }
    .nav-pills .nav-link .badge { margin-left: 5px; vertical-align: middle; }
    .tab-content { border: 1px solid #dee2e6; padding: 20px; background: #fff; margin-top: -1px; }
    .row.mt-3 { margin-top: 0 !important; margin-bottom: 12px; }
</style>
@endpush
