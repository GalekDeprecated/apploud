<?php

namespace App\Libs\Gitlab\Api\Group;

use App\Libs\Gitlab\Api\Permission;
use App\Libs\Gitlab\Api\Project;

class Group implements \JsonSerializable
{
    private int $id;

    private int|null $parentId;

    private string $name;

    private Permission $permission;

    /** @var Project[] */
    private array $projects = [];

    /** @var Group[] */
    private array $groups = [];

    public static function create(array $data): static
    {
        $group = new static();
        $group->id = $data['id'];
        $group->parentId = $data['parent_id'];
        $group->name = $data['name'];
        $group->permission = Permission::create($data['access_level']);

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
            'permission' => $this->permission->getPermission(),
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
}