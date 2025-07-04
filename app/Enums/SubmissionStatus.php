<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/7/4
 * Time: 15:30
 */
declare(strict_types=1);

namespace App\Enums;

enum SubmissionStatus: int
{
    // 待审核
    case PENDING = 0;
    case APPROVED = 1;
    case REJECTED = 2;
}