<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportFileStat extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'run_id',
        'file_name',
        'category',
        'type_slug',
        'year',
        'wave',
        'inserted_count',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'wave' => 'integer',
            'inserted_count' => 'integer',
            'processed_at' => 'datetime',
        ];
    }

    public function run(): BelongsTo
    {
        return $this->belongsTo(ImportRun::class, 'run_id');
    }
}
