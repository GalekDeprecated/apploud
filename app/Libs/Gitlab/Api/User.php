<?php

namespace App\Libs\Gitlab\Api;

use App\Libs\Gitlab\Api\Group;
use App\Libs\Gitlab\Api\Project;

class User implements UserInterface
{
    private int $id;

    private string $name;

    private string $username;

    private Permission $permission;

    /** @var Group[] */
    private array $groups = [];

    /** @var Project[] */
    private array $projects = [];

    protected function __construct()
    {
    }

    public static function create(array $data): static
    {
        $user = new static();
        $user->id = $data['id'];
        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->permission = Permission::create($data['access_level']);

        return $user;
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

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return Group[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return Project[]
     */
    public function getProjects(): array
    {
        return $this->projects;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'groups' => $this->groups,
            'projects' => $this->projects,
        ];
    }
}