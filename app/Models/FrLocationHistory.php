<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrLocationHistory extends Model
{
    protected $fillable = ['former_rebel_id', 'placement_address', 'latitude', 'longitude', 'updated_by'];

    public function formerRebel(): BelongsTo
    {
        return $this->belongsTo(FormerRebel::class);
    }
}
