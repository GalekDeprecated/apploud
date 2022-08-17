<?php

namespace App\Libs\Gitlab;

class Project implements AccessLevel, \JsonSerializable
{
    private int $id;

    private string $name;

    private int $accessLevel;

    public function __construct(int $id, string $name, int $accessLevel)
    {
        $this->id = $id;
        $this->name = $name;
        $this->accessLevel = $accessLevel;
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
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getAccessLevel(): int
    {
        return $this->accessLevel;
    }

    /**
     * @return string
     */
    public function getAccessLevelName(): string
    {
        return self::ACCESS[$this->accessLevel];
    }

    /**
     * @param int $accessLevel
     */
    public function setAccessLevel(int $accessLevel): void
    {
        $this->accessLevel = $accessLevel;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'access_level' => $this->accessLevel,
            'access_level_name' => $this->getAccessLevelName(),
        ];
    }
}