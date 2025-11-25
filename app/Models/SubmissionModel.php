<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/11/25
 * Time: 17:06 下午
 */
declare(strict_types=1);

namespace App\Models;

class SubmissionModel extends BaseModel
{
    protected $table = 'submission';
    protected $primaryKey = 'id';
    protected $returnType = 'App\Entities\Submission';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'uuid',
        'ulid',
        'cid',
        'website_name',
        'url',
        'description',
        'favicon',
        'rating',
        'status',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $dateFormat = 'datetime';
    protected $validationRules = [
        'uuid' => 'required|min_length[36]|max_length[36]',
        'ulid' => 'required|min_length[26]|max_length[26]',
    ];
    protected $validationMessages = [
        'uuid' => [
            'required' => 'uuid 列为必填项',
            'min_length' => 'uuid 长度为36个字符',
            'max_length' => 'uuid 长度为36个字符',
        ],
        'ulid' => [
            'required' => 'ulid 列为必填项',
            'min_length' => 'ulid 长度为26个字符',
            'max_length' => 'ulid 长度为26个字符',
        ],
    ];
    protected $skipValidation = false;

}

