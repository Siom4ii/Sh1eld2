<?php

namespace App\Http\Controllers\Ib39;

use App\Http\Controllers\Controller;
use App\Models\MapBarangay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AreaController extends Controller
{
    public function index(Request $request): View
    {
        $areas = MapBarangay::query()
            ->when($request->municipality, fn ($q, $m) => $q->where('municipality', $m))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, fn ($q, $s) => $q->where('barangay', 'like', "%{$s}%"))
            ->orderBy('municipality')->orderBy('barangay')
            ->paginate(20)->withQueryString();

        $municipalities = MapBarangay::select('municipality')->distinct()
            ->whereNotNull('municipality')->orderBy('municipality')->pluck('municipality');

        return view('ib39.areas.index', compact('areas', 'municipalities'));
    }

    /** Set an area's FR count → recompute status + infestation colour, log history. */
    public function update(Request $request, MapBarangay $area): RedirectResponse
    {
        $data = $request->validate([
            'frs' => ['required', 'integer', 'min:0'],
        ]);

        $class = MapBarangay::classify($data['frs']);

        DB::transaction(function () use ($area, $data, $class) {
            $area->update([
                'frs' => $data['frs'],
                'rebels' => $data['frs'],
                'status' => $class['status'],
                'infestation_color' => $class['color'],
            ]);
            $area->colorHistories()->create([
                'status' => $class['status'],
                'color' => $class['color'],
                'frs' => $data['frs'],
            ]);
        });

        return back()->with('success', "{$area->barangay} set to {$class['status']} ({$data['frs']} FRs).");
    }

    public function map(): View
    {
        return view('ib39.map');
    }

    /** Former-rebel points for the Leaflet operational map. */
    public function mapData(): JsonResponse
    {
        $rows = \App\Models\FormerRebel::whereNotNull('latitude')->whereNotNull('longitude')
            ->get(['id', 'firstname', 'lastname', 'placement_address', 'latitude', 'longitude', 'status', 'batch_year'])
            ->map(fn ($fr) => [
                'name' => trim("{$fr->firstname} {$fr->lastname}"),
                'address' => $fr->placement_address,
                'lat' => (float) $fr->latitude,
                'lng' => (float) $fr->longitude,
                'status' => $fr->status,
                'batch' => $fr->batch_year,
            ]);

        return response()->json($rows);
    }
}
