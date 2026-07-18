<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrEducationWork extends Model
{
    protected $fillable = ['former_rebel_id', 'educational_attainment', 'occupation'];

    public function formerRebel(): BelongsTo
    {
        return $this->belongsTo(FormerRebel::class);
    }
}
