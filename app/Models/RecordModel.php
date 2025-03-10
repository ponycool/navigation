<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/03/07
 * Time: 16:05 下午
 */
declare(strict_types=1);

namespace App\Models;

class RecordModel extends BaseModel
{
    protected $table = 'record';
    protected $primaryKey = 'id';
    protected $returnType = 'App\Entities\Record';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'uuid',
        'website_id',
        'website_name',
        'url',
        'browser',
        'version',
        'mobile',
        'platform',
        'referrer',
        'user_agent',
        'ip',
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

