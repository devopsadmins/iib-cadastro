<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImportRun extends Model
{
    public $timestamps = false;

    protected $fillable = ['source_dir', 'started_at', 'finished_at', 'notes'];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function fileStats(): HasMany
    {
        return $this->hasMany(ImportFileStat::class, 'run_id');
    }
}
