<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrProgramStatus extends Model
{
    protected $fillable = ['former_rebel_id', 'reintegration_status', 'reintegration_date', 'updated_by'];

    protected function casts(): array
    {
        return ['reintegration_date' => 'date'];
    }

    public function formerRebel(): BelongsTo
    {
        return $this->belongsTo(FormerRebel::class);
    }
}
