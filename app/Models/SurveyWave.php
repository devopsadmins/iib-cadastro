<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyWave extends Model
{
    public $timestamps = false;

    protected $fillable = ['year', 'wave', 'label'];

    public function experts(): BelongsToMany
    {
        return $this->belongsToMany(RegistryExpert::class, 'registry_expert_waves', 'wave_id', 'expert_id');
    }

    public function mailingContacts(): BelongsToMany
    {
        return $this->belongsToMany(MailingContact::class, 'mailing_contact_waves', 'wave_id', 'mailing_contact_id');
    }

    public function registeredExperts(): HasMany
    {
        return $this->hasMany(RegistryExpert::class, 'registration_wave_id');
    }
}
