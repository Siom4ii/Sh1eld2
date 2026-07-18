<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrGovernmentAssistance extends Model
{
    protected $fillable = ['former_rebel_id', 'assistance_type', 'date_received', 'status', 'certificate_file'];

    protected function casts(): array
    {
        return ['date_received' => 'date'];
    }

    public function formerRebel(): BelongsTo
    {
        return $this->belongsTo(FormerRebel::class);
    }
}
