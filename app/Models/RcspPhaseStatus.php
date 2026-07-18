<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RcspPhaseStatus extends Model
{
    protected $fillable = [
        'rcsp_barangay_id',
        'phase0_completed', 'phase1_completed', 'phase2_completed',
        'phase3_completed', 'phase4_completed', 'phase5_completed',
    ];

    protected function casts(): array
    {
        return [
            'phase0_completed' => 'boolean',
            'phase1_completed' => 'boolean',
            'phase2_completed' => 'boolean',
            'phase3_completed' => 'boolean',
            'phase4_completed' => 'boolean',
            'phase5_completed' => 'boolean',
        ];
    }

    public function rcspBarangay(): BelongsTo
    {
        return $this->belongsTo(RcspBarangay::class);
    }
}
