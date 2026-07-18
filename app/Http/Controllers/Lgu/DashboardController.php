<?php

namespace App\Http\Controllers\Lgu;

use App\Http\Controllers\Controller;
use App\Models\RcspBarangay;
use App\Models\RcspForm;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $muniId = auth()->user()->municipality_id;
        $base = RcspBarangay::query()->when($muniId, fn ($q) => $q->where('municipality_id', $muniId));

        $rcsp = [
            'total' => (clone $base)->count(),
            'not_started' => (clone $base)->whereIn('status', ['Pending', 'Not Started'])->count(),
            'ongoing' => (clone $base)->where('status', 'Ongoing')->count(),
            'completed' => (clone $base)->where('status', 'Completed')->count(),
        ];

        $statusChart = collect(['Pending', 'Ongoing', 'Completed'])
            ->mapWithKeys(fn ($s) => [$s => (clone $base)->where('status', $s)->count()]);

        $bgyIds = (clone $base)->pluck('id');
        $recentDocuments = RcspForm::whereIn('rcsp_barangay_id', $bgyIds)
            ->with(['rcspBarangay.barangay', 'phase'])
            ->latest()->take(6)->get();

        return view('lgu.dashboard', [
            'rcsp' => $rcsp,
            'statusChart' => $statusChart,
            'recentDocuments' => $recentDocuments,
            'userFullname' => auth()->user()->name,
            'municipalName' => auth()->user()->municipality?->name ?? 'your municipality',
        ]);
    }
}
