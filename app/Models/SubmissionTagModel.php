<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/07/05
 * Time: 14:45 下午
 */
declare(strict_types=1);

namespace App\Models;

class SubmissionTagModel extends BaseModel
{
    protected $table = 'submission_tag';
    protected $primaryKey = 'id';
    protected $returnType = 'App\Entities\SubmissionTag';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'uuid',
        'submission_id',
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

