<?php

namespace App\Enum;

enum Status: string
{
    case pending = 'pending';
    case completed = 'completed';
    case archived = 'archived';
}
