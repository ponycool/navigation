<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/04/02
 * Time: 16:46 下午
 */
declare(strict_types=1);

namespace App\Models;

class WebsiteTagModel extends BaseModel
{
    protected $table = 'website_tag';
    protected $primaryKey = 'id';
    protected $returnType = 'App\Entities\WebsiteTag';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'uuid',
        'website_id',
        'tag_id',
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

