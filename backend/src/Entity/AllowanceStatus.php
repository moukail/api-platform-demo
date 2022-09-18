<?php

namespace App\Entity;

enum AllowanceStatus: string
{
    case PENDING = 'pending';
    case STOPPED = 'stopped';
    case ACTIVE = 'active';
}