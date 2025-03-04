<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/3/4
 * Time: 15:09
 */
declare(strict_types=1);

namespace App\Controllers\Api\Link\Target;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Enums\LinkTarget;

class Query extends Base
{
    /**
     * 获取链接打开方式列表
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        $list = [];
        foreach (LinkTarget::cases() as $case) {
            $value = match ($case->value) {
                '_blank' => '在新窗口中打开被链接文档',
                '_self' => '在相同的框架中打开被链接文档',
                '_parent' => '在父框架中打开被链接文档',
                '_top' => '在当前窗口中打开被链接文档',
            };
            $list[$case->value] = $value;
        }
        $data = [
            'code' => Code::OK,
            'message' => '获取链接打开方式列表成功',
            'list' => $list
        ];
        $this->render($data);
    }
}