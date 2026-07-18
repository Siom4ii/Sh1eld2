<?php

namespace App\Http\Controllers\Afp;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\RcspBarangay;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * AFP is a read-only observer of RCSP barangay progress across all
 * municipalities (no create/edit — monitoring only).
 */
class RcspController extends Controller
{
    public function index(Request $request): View
    {
        $barangays = RcspBarangay::query()
            ->with(['barangay', 'municipality'])
            ->when($request->municipality_id, fn ($q, $m) => $q->where('municipality_id', $m))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->orderBy('municipality_id')->orderByDesc('current_phase')
            ->paginate(20)->withQueryString();

        return view('afp.rcsp.index', [
            'barangays' => $barangays,
            'municipalities' => Municipality::orderBy('name')->get(),
        ]);
    }
}
