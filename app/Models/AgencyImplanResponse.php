<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgencyImplanResponse extends Model
{
    protected $fillable = ['gov_agency_id', 'implementation_id', 'response_status', 'rejection_reason'];

    public function govAgency(): BelongsTo
    {
        return $this->belongsTo(GovAgency::class);
    }

    public function implementation(): BelongsTo
    {
        return $this->belongsTo(Implementation::class);
    }
}
