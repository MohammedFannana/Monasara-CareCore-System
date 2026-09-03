<?php

namespace App\Enums;

enum OrphanRole: string
{
    case WAITING = 'waiting';
    case CANDIDATE = 'candidate';
    case AUDITOR = 'auditor';
    case CERTIFIED = 'certified';
    case SPONSORED = 'sponsored';
    case REJECTED = 'rejected';
    case ARCHIVED = 'archived';
}
