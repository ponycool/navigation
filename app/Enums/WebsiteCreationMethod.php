<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/7
 * Time: 14:00
 */
declare(strict_types=1);

namespace App\Enums;

enum WebsiteCreationMethod: string
{
    case MANUAL = '手动创建';
    case COLLECTION = '用户收录';
    case IMPORT = '书签导入';
    case CLOUD_SYNC = '云端同步';
}
