<?php
/**
 * Created by PhpStorm.
 * User: Pony
 * Date: 2025/04/02
 * Time: 16:45 下午
 */
declare(strict_types=1);

namespace App\Entities;

use Exception;

class Tag extends Base
{
    protected int $id = 0;
    protected string $uuid;
    protected string $tag_name;
    protected string $tag_description;
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
                '初始化 Tag Entity 失败，error：{msg}',
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
    public function setId(int $id): Tag
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
    public function setUuid(string $uuid = ''): Tag
    {
        $this->uuid = $uuid ?: $this->generateUuid();
        $this->attributes['uuid'] = $this->uuid;
        return $this;
    }

    /**
     * @return string
     */
    public function getTagName(): string
    {
        return $this->tag_name;
    }

    /**
     * @param string $tag_name
     * @return $this
     */
    public function setTagName(string $tag_name): Tag
    {
        $this->tag_name = $tag_name;
        $this->attributes['tag_name'] = $this->tag_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTagDescription(): string
    {
        return $this->tag_description;
    }

    /**
     * @param string $tag_description
     * @return $this
     */
    public function setTagDescription(string $tag_description): Tag
    {
        $this->tag_description = $tag_description;
        $this->attributes['tag_description'] = $this->tag_description;
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
