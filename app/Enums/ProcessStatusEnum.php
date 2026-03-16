<?php

namespace App\Enums;

class ProcessStatusEnum: string
{
    const STARTED = 1;
    const COMPLETED = 2;
    const FAILED = 3;
}
