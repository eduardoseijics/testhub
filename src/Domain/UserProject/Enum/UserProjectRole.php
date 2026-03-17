<?php

namespace App\Domain\UserProject\Enum;

enum UserProjectRole: string
{
    case OWNER  = 'owner';
    case ADMIN  = 'admin';
    case MEMBER = 'member';
    case VIEWER = 'viewer';
}