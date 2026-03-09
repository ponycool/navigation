<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2026/3/6
 * Time: 10:00
 */
declare(strict_types=1);

namespace App\Controllers\Api\Website\Submission;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\SubmissionService;
use Exception;

class Query extends Base
{
    public function index(): void
    {
        $this->postFilter();
        $svc = new SubmissionService();
        $rules = $svc->getQueryRules();
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $list = $svc->getList($params);
            $data = [
                'code' => Code::OK,
                'message' => '获取收录记录成功'
            ];
            $data = array_merge($data, $list);
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '获取收录记录失败'
            ];
        }
        $this->render($data);
    }
}
