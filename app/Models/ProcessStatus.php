<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcessStatus extends Model
{
    public function reportProcesses(): HasMany
    {
        return $this->hasMany(ReportProcess::class);
    }
}
