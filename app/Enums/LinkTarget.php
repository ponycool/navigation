<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/4
 * Time: 15:12
 */
declare(strict_types=1);

namespace App\Enums;

enum LinkTarget: string
{
    // 默认。在相同的框架中打开被链接文档
    case SELF = '_self';
    // 在新窗口中打开被链接文档
    case BLANK = '_blank';
    // 在父框架集中打开被链接文档
    case PARENT = '_parent';
    // 在整个窗口中打开被链接文档
    case TOP = '_top';
}