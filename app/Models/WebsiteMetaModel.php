<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/07/05
 * Time: 14:44 下午
 */
declare(strict_types=1);

namespace App\Models;

class WebsiteMetaModel extends BaseModel
{
    protected $table = 'website_meta';
    protected $primaryKey = 'id';
    protected $returnType = 'App\Entities\WebsiteMeta';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'uuid',
        'website_id',
        'meta_key',
        'meta_value',
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

