<?php

namespace App\Enums;

enum ProcessStatusEnum: string
{
    case STARTED = 'started';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
