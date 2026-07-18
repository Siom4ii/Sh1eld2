<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrSkill extends Model
{
    protected $fillable = ['former_rebel_id', 'skill_name', 'proficiency_level'];

    public function formerRebel(): BelongsTo
    {
        return $this->belongsTo(FormerRebel::class);
    }
}
