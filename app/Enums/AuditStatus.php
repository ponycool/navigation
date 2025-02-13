<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/2/13
 * Time: 16:18
 */
declare(strict_types=1);

namespace App\Enums;

enum AuditStatus: string
{
    case PENDING = '待审核';
    case APPROVED='已通过';
    case REJECTED='已拒绝';
}