<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/07/05
 * Time: 14:45 下午
 */
declare(strict_types=1);

namespace App\Entities;

use Exception;

class SubmissionMeta extends Base
{
    protected int $id = 0;
    protected string $uuid;
    protected int $submission_id;
    protected string $meta_key;
    protected string $meta_value;
    protected string $created_at;
    protected string $updated_at;
    protected string $deleted_at;
    protected int $deleted;
    protected $dates = [
    ];
    protected $casts = [
        'deleted' => 'boolean'
    ];

    public function __construct(array $data = null)
    {
        parent::__construct($data);
        try {
            $this->setUuid();
        } catch (Exception $e) {
            log_message(
                'error',
                '初始化 SubmissionMeta Entity 失败，error：{msg}',
                ['msg' => $e->getMessage()]
            );
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): SubmissionMeta
    {
        $this->id = $id;
        $this->attributes['id'] = $this->id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return $this
     * @throws Exception
     */
    public function setUuid(string $uuid = ''): SubmissionMeta
    {
        $this->uuid = $uuid ?: $this->generateUuid();
        $this->attributes['uuid'] = $this->uuid;
        return $this;
    }

    /**
     * @return int
     */
    public function getSubmissionId(): int
    {
        return $this->submission_id;
    }

    /**
     * @param int $submission_id
     * @return $this
     */
    public function setSubmissionId(int $submission_id): SubmissionMeta
    {
        $this->submission_id = $submission_id;
        $this->attributes['submission_id'] = $this->submission_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaKey(): string
    {
        return $this->meta_key;
    }

    /**
     * @param string $meta_key
     * @return $this
     */
    public function setMetaKey(string $meta_key): SubmissionMeta
    {
        $this->meta_key = $meta_key;
        $this->attributes['meta_key'] = $this->meta_key;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaValue(): string
    {
        return $this->meta_value;
    }

    /**
     * @param string $meta_value
     * @return $this
     */
    public function setMetaValue(string $meta_value): SubmissionMeta
    {
        $this->meta_value = $meta_value;
        $this->attributes['meta_value'] = $this->meta_value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * @return string
     */
    public function getDeletedAt(): string
    {
        return $this->deleted_at;
    }

    /**
     * @return int
     */
    public function getDeleted(): int
    {
        return $this->deleted;
    }

}
