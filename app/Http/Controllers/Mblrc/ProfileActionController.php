<?php

namespace App\Http\Controllers\Mblrc;

use App\Http\Controllers\Controller;
use App\Models\FormerRebel;
use App\Models\FrGovernmentAssistance;
use App\Models\FrSkill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * AJAX/action endpoints backing the FR profile page widgets:
 * program status, geolocation, skills, assistance, education/work.
 */
class ProfileActionController extends Controller
{
    public function updateProgramStatus(Request $request, FormerRebel $formerRebel): JsonResponse
    {
        $data = $request->validate([
            'reintegration_status' => ['required', Rule::in(['Not-Started', 'On-going', 'Completed'])],
            'reintegration_date' => ['nullable', 'date'],
        ]);

        $formerRebel->programStatus()->updateOrCreate(
            ['former_rebel_id' => $formerRebel->id],
            [
                'reintegration_status' => $data['reintegration_status'],
                'reintegration_date' => $data['reintegration_date'] ?? now()->toDateString(),
                'updated_by' => $request->user()->name,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function saveLocation(Request $request, FormerRebel $formerRebel): JsonResponse
    {
        $data = $request->validate([
            'placement_address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        DB::transaction(function () use ($formerRebel, $data, $request) {
            $formerRebel->update([
                'placement_address' => $data['placement_address'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ]);
            $formerRebel->locationHistories()->create([
                'placement_address' => $data['placement_address'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'updated_by' => $request->user()->name,
            ]);
        });

        return response()->json(['success' => true]);
    }

    public function locationHistory(FormerRebel $formerRebel): JsonResponse
    {
        return response()->json(
            $formerRebel->locationHistories()->latest()->get()
        );
    }

    public function storeSkill(Request $request, FormerRebel $formerRebel): JsonResponse
    {
        $data = $request->validate([
            'skill_name' => ['required', 'string', 'max:255'],
            'proficiency_level' => ['required', Rule::in(['Beginner', 'Intermediate', 'Advanced'])],
        ]);

        // dedupe by name
        $skill = $formerRebel->skills()->firstOrCreate(
            ['skill_name' => $data['skill_name']],
            ['proficiency_level' => $data['proficiency_level']]
        );

        return response()->json(['success' => true, 'skill' => $skill]);
    }

    public function destroySkill(FrSkill $skill): JsonResponse
    {
        $skill->delete();

        return response()->json(['success' => true]);
    }

    /** Static vocational-skill autocomplete list (replaces get_skills_suggestions). */
    public function skillSuggestions(Request $request): JsonResponse
    {
        $all = [
            'Welding', 'Carpentry', 'Masonry', 'Plumbing', 'Electrical Installation',
            'Automotive Servicing', 'Driving', 'Farming', 'Livestock Raising', 'Fishing',
            'Cooking', 'Baking', 'Dressmaking', 'Tailoring', 'Hairdressing',
            'Computer Literacy', 'Electronics Repair', 'Handicrafts', 'Painting', 'Landscaping',
        ];
        $term = strtolower($request->query('term', ''));
        $matches = $term
            ? array_values(array_filter($all, fn ($s) => str_contains(strtolower($s), $term)))
            : $all;

        return response()->json($matches);
    }

    public function storeAssistance(Request $request, FormerRebel $formerRebel): JsonResponse
    {
        $data = $request->validate([
            'assistance_type' => ['required', 'string', 'max:255'],
            'date_received' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['Pending', 'In Progress', 'Completed'])],
            'certificate' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,pdf', 'max:25600'],
        ]);

        $path = null;
        if ($request->hasFile('certificate')) {
            $path = $request->file('certificate')->store('fr/certificates', 'public');
        }

        $assistance = $formerRebel->assistances()->create([
            'assistance_type' => $data['assistance_type'],
            'date_received' => $data['date_received'] ?? null,
            'status' => $data['status'] ?? 'Pending',
            'certificate_file' => $path,
        ]);

        return response()->json(['success' => true, 'assistance' => $assistance]);
    }

    public function destroyAssistance(FrGovernmentAssistance $assistance): JsonResponse
    {
        if ($assistance->certificate_file) {
            Storage::disk('public')->delete($assistance->certificate_file);
        }
        $assistance->delete();

        return response()->json(['success' => true]);
    }

    public function updateEducationWork(Request $request, FormerRebel $formerRebel): JsonResponse
    {
        $data = $request->validate([
            'educational_attainment' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
        ]);

        $formerRebel->educationWorks()->create($data);
        // keep the FR's denormalized occupation in sync
        if (! empty($data['occupation'])) {
            $formerRebel->update(['occupation' => $data['occupation']]);
        }

        return response()->json(['success' => true]);
    }
}
