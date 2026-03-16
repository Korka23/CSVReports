<?php

namespace App\Models;

use App\Enums\ProcessStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReportProcess extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => ProcessStatusEnum::class
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->pid = Str::uuid();
        });
    }
}
