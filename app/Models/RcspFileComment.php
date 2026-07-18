<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RcspFileComment extends Model
{
    protected $fillable = ['rcsp_form_id', 'rcsp_phase_id', 'rcsp_activity_id', 'user_id', 'text'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(RcspForm::class, 'rcsp_form_id');
    }
}
