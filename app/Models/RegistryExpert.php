<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RegistryExpert extends Model
{
    protected $fillable = [
        'expert_type_id',
        'first_name',
        'last_name',
        'company',
        'occupation',
        'address',
        'city',
        'postal_code',
        'phone',
        'email',
        'registration_wave_id',
        'registration_wave_note',
        'is_active',
        'deactivated_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'deactivated_at' => 'datetime',
        ];
    }

    public function expertType(): BelongsTo
    {
        return $this->belongsTo(ExpertType::class);
    }

    public function registrationWave(): BelongsTo
    {
        return $this->belongsTo(SurveyWave::class, 'registration_wave_id');
    }

    public function waves(): BelongsToMany
    {
        return $this->belongsToMany(SurveyWave::class, 'registry_expert_waves', 'expert_id', 'wave_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
