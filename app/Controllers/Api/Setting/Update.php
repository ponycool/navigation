<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2024/2/23
 * Time: 10:52
 */
declare(strict_types=1);

namespace App\Controllers\Api\Setting;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Services\SettingService;
use Exception;

class Update extends Base
{
    /**
     * 更新系统配置
     * @return void
     */
    public function index(): void
    {
        $this->postFilter();
        $svc = new SettingService();
        $rules = $svc->getUpdateRules();
        $this->verifyJsonInputByRules($rules);
        $params = $this->getJsonInputParams();
        try {
            $res = $svc->updateSetting($params);
            if ($res !== true) {
                throw new Exception($res);
            }
            $data = [
                'code' => Code::OK,
                'message' => '更新系统配置成功',
            ];
        } catch (Exception $e) {
            $data = [
                'code' => Code::FAIL,
                'message' => $e->getMessage() ?: '更新系统配置失败'
            ];
        }
        $this->render($data);
    }
}