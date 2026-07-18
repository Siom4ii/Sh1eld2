<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GovAgency;
use App\Models\Implementation;
use App\Models\RcspBarangay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ImplanController extends Controller
{
    public function index(): View
    {
        $all = Implementation::with('taggings.govAgency')->latest()->get();

        $tabs = [
            'verification' => $all->where('status', 'for verification'),
            'ongoing' => $all->where('status', 'ongoing'),
            'not_started' => $all->where('status', 'not yet started'),
            'verified' => $all->where('status', 'verified'),
            // plans with a rejected tag can be reassigned
            'reassign' => $all->filter(fn ($i) => $i->taggings->contains('status', 'Rejected')),
        ];

        // Lookup: rcsp_barangay id => "Barangay - Municipality" for the Target Area column.
        $areaNames = RcspBarangay::with(['barangay', 'municipality'])->get()
            ->mapWithKeys(fn ($r) => [
                $r->id => ($r->barangay?->name ?? "Barangay #{$r->barangay_id}").' - '.($r->municipality?->name ?? ''),
            ]);

        $allAgencies = GovAgency::orderBy('acronym')->get();

        return view('admin.implan.index', compact('tabs', 'areaNames', 'allAgencies'));
    }

    public function show(Implementation $implan): View
    {
        $implan->load('files', 'photos', 'taggings.govAgency', 'responses.govAgency');

        return view('admin.implan.show', [
            'implan' => $implan,
            'areaNames' => RcspBarangay::with('barangay')->whereIn('id', $implan->target_areas ?? [])
                ->get()->map(fn ($r) => $r->barangay?->name ?? "Barangay #{$r->barangay_id}"),
            'assignedAgencies' => GovAgency::whereIn('id', $implan->agencies ?? [])->get(),
            'allAgencies' => GovAgency::orderBy('acronym')->get(),
        ]);
    }

    public function verify(Implementation $implan): RedirectResponse
    {
        $implan->update(['status' => 'verified']);

        return back()->with('success', 'Implementation plan verified.');
    }

    /** Reassign a plan (with rejected agencies) to a new set of agencies. */
    public function reassign(Request $request, Implementation $implan): RedirectResponse
    {
        $data = $request->validate([
            'agencies' => ['required', 'array', 'min:1'],
            'agencies.*' => ['integer', 'exists:gov_agencies,id'],
        ]);

        DB::transaction(function () use ($implan, $data) {
            // retire rejected taggings
            $implan->taggings()->where('status', 'Rejected')->update(['status' => 'Reassigned']);

            $agencies = array_map('intval', $data['agencies']);
            $implan->update([
                'agencies' => $agencies,
                'status' => 'for verification',
            ]);

            // fresh pending response + tagging for each newly assigned agency
            foreach ($agencies as $agencyId) {
                $implan->responses()->updateOrCreate(
                    ['gov_agency_id' => $agencyId],
                    ['response_status' => 'pending', 'rejection_reason' => null]
                );
                $implan->taggings()->updateOrCreate(
                    ['gov_agency_id' => $agencyId],
                    ['status' => 'Pending', 'reason' => null]
                );
            }
        });

        return back()->with('success', 'Plan reassigned to selected agencies.');
    }
}
