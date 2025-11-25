<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/11/25
 * Time: 16:47
 */
declare(strict_types=1);

namespace App\Controllers\Api\Open\Website\Submission;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\SubmissionService as Svc;
use Exception;

class Create extends Base
{
    public function index(): void
    {
        $this->postFilter();
        $svc = new Svc();
        $rules = $svc->getCreateRules();
        $this->verifyJsonInputByRules($rules);
        try {
            $params = $this->getJsonInputParams();
            $svc->create($params);
            $data = [
                'code' => Code::OK,
                'message' => '网址收录成功'
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '网址收录失败'
            ];
        }
        $this->render($data);
    }
}