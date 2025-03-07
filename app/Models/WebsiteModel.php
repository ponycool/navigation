<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/03/07
 * Time: 14:06 下午
 */
declare(strict_types=1);

namespace App\Models;

class WebsiteModel extends BaseModel
{
    protected $table = 'website';
    protected $primaryKey = 'id';
    protected $returnType = 'App\Entities\Website';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'uuid',
        'cid',
        'website_name',
        'url',
        'icon',
        'icon_url',
        'description',
        'rating',
        'click_count',
        'check_count',
        'last_check_time',
        'offline_count',
        'health_status',
        'sort_index',
        'creation_method',
        'status',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $dateFormat = 'datetime';
    protected $validationRules = [
        'uuid' => 'required|min_length[36]|max_length[36]',
    ];
    protected $validationMessages = [
        'uuid' => [
            'required' => 'uuid 列为必填项',
            'min_length' => 'uuid 长度为36个字符',
            'max_length' => 'uuid 长度为36个字符',
        ],
    ];
    protected $skipValidation = false;

}

