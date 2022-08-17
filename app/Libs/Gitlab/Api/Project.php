<?php

namespace App\Libs\Gitlab\Api;

class Project implements \JsonSerializable
{
    private int $id;

    private string $name;

    private int $creatorId;

    public static function create(array $data): static
    {
        $project = new static();
        $project->id = $data['id'];
        $project->name = $data['name'];
        $project->creatorId = $data['creator_id'];

        return $project;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'creatorId' => $this->creatorId,
        ];
    }

    /**
     * @return int
     */
    public function getCreatorId(): int
    {
        return $this->creatorId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}