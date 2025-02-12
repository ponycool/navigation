<?php
/**
 * Created By PhpStorm
 * User: Pony
 * Data: 2025/2/12
 * Time: 14:39
 */
declare(strict_types=1);

namespace App\Controllers\Api\Setting;

use App\Controllers\Api\Base;
use App\Enums\Code;
use App\Enums\Setting as SettingEnum;
use App\Services\SettingService;

class Query extends Base
{
    /**
     * 获取配置
     * @return void
     */
    public function index(): void
    {
        $svc = new SettingService();
        $settings = $svc->get();
        $settingItems = SettingEnum::cases();
        $data = [
            'code' => Code::OK,
            'message' => '获取系统配置成功'
        ];
        foreach ($settingItems as $item) {
            $data[$item->value] = null;
        }
        if (!empty($settings)) {
            foreach ($settings as $setting) {
                $data[$setting['key']] = $setting['value'];
            }
        }
        $this->render($data);
    }
}