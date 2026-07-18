<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImplementationFile extends Model
{
    protected $fillable = ['implementation_id', 'file_name', 'description', 'pdf'];

    public function implementation(): BelongsTo
    {
        return $this->belongsTo(Implementation::class);
    }
}
