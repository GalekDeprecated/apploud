<?php

namespace App\Libs\Gitlab\Api;


use App\Libs\Gitlab\Api\Group\Member;

class Group implements \JsonSerializable
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var int|null
     */
    private int|null $parentId;

    /**
     * @var string
     */
    private string $name;

    /** @var Project[] */
    private array $projects = [];

    /** @var Group[] */
    private array $subGroups = [];

    /** @var Member[] */
    private array $members = [];

    /**
     * @param array $data
     * @param Group[] $subGroups
     * @param Member[] $members
     * @return static
     */
    public static function create(array $data, array $subGroups = [], array $members = []): static
    {
        $group = new static();
        $group->id = $data['id'];
        $group->parentId = $data['parent_id'];
        $group->name = $data['name'];
        $group->subGroups = $subGroups;
        $group->members = $members;

        foreach ($data['projects'] as $project) {
            $project2 = Project::create($project);
            $group->projects[$project2->getId()] = $project2;
        }

        return $group;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'parentId' => $this->parentId,
            'name' => $this->name,
            'members' => $this->members,
            'subGroups' => $this->subGroups,
            'projects' => $this->projects,
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Project[]
     */
    public function getProjects(): array
    {
        return $this->projects;
    }

    /**
     * @param UserInterface[] $members
     */
    public function setMembers(array $members): void
    {
        $this->members = $members;
    }

    /**
     * @param Group[] $subGroups
     */
    public function setSubGroups(array $subGroups): void
    {
        $this->subGroups = $subGroups;
    }

    /**
     * @return Member[]
     */
    public function getMembers(): array
    {
        return $this->members;
    }
}