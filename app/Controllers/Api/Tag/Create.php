<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/8/16
 * Time: 16:20
 */
declare(strict_types=1);

namespace App\Controllers\Api\Tag;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\TagService;
use Exception;

class Create extends Base
{
    public function index(): void
    {
        $this->postFilter();
        $svc = new TagService();
        $rules = $svc->getCreateRules();
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $svc->create($params);
            $data = [
                'code' => Code::OK,
                'message' => '创建标签成功',
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '创建标签失败'
            ];
        }
        $this->render($data);
    }
}