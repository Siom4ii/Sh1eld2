<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Implementation extends Model
{
    protected $fillable = [
        'lgu_user_id', 'uploaded_at', 'issues', 'program', 'target_areas', 'agencies',
        'beneficiaries', 'outcome', 'resources', 'support', 'duration', 'status',
        'type_gov', 'sources', 'remarks', 'tagging',
    ];

    protected function casts(): array
    {
        return [
            'uploaded_at' => 'date',
            'target_areas' => 'array',
            'agencies' => 'array',
        ];
    }

    public function lguUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lgu_user_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ImplementationFile::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ImplementationPhoto::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(AgencyImplanResponse::class);
    }

    public function taggings(): HasMany
    {
        return $this->hasMany(ImplementationTagging::class);
    }
}
