<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MailingContact extends Model
{
    protected $fillable = [
        'interviewee_name',
        'company',
        'occupation',
        'city',
        'linkedin_url',
        'company_website',
        'merco_approval_status',
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

    public function waves(): BelongsToMany
    {
        return $this->belongsToMany(SurveyWave::class, 'mailing_contact_waves', 'mailing_contact_id', 'wave_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
