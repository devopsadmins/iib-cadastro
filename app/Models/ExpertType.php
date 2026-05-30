<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpertType extends Model
{
    public $timestamps = false;

    protected $fillable = ['slug', 'name', 'sort_order'];

    public function experts(): HasMany
    {
        return $this->hasMany(RegistryExpert::class);
    }
}
