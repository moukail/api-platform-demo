<?php

namespace App\Entity;

enum AllowanceStatus: string
{
    case PENDING = 'pending';
    case STOPPED = 'stopped';
    case ACTIVE = 'active';

    public function label(): string
    {
        return match($this) {
            static::PENDING => 'Pending',
            static::ACTIVE => 'Active',
            static::STOPPED => 'Stopped',
        };
    }
}