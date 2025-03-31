<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/27
 * Time: 16:11
 */
declare(strict_types=1);

namespace App\Enums;

enum LockStatus: int
{
    case UNLOCKED = 0;
    case LOCKED = 1;
}
