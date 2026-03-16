<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ReportProcess extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->pid = Str::uuid();
        });
    }

    public function processStatus(): HasMany
    {
        return $this->hasMany(ProcessStatus::class);
    }
}
