<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImplementationTagging extends Model
{
    protected $fillable = ['implementation_id', 'gov_agency_id', 'status', 'reason'];

    public function implementation(): BelongsTo
    {
        return $this->belongsTo(Implementation::class);
    }

    public function govAgency(): BelongsTo
    {
        return $this->belongsTo(GovAgency::class);
    }
}
