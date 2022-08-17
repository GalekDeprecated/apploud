<?php

namespace App\Libs\Gitlab\Response;

class User implements \JsonSerializable
{
    private string $name;

    private string $username;

    /** @var Group[]  */
    private array $groups = [];

    /** @var Project[] */
    private array $projects = [];

    /**
     * @param string $name
     * @param string $username
     * @param Group[] $groups
     * @param Project[] $projects
     * @return static
     */
    public static function create(string $name, string $username, array $groups = [], array $projects = []): static
    {
        $user = new static();
        $user->name = $name;
        $user->username = $username;

        foreach ($groups as $group) {
            if (!$group instanceof Group) {
                throw new RuntimeException('Unable create user. Group ' . $group::class . ' is not instance of ' . Group::class);
            }
            $user->groups[] = $group;
        }

        foreach ($projects as $project) {
            if (!$project instanceof Project) {
                throw new RuntimeException('Unable create user. Project ' . $project::class . ' is not instance of ' . Project::class);
            }
            $user->projects[] = $project;
        }

        return $user;
    }

    public function addProject(Project $project): void
    {
        $this->projects[] = $project;
    }

    public function addGroup(Group $group): void
    {
        $this->groups[] = $group;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'groups' => $this->groups,
            'projects' => $this->projects,
        ];
    }
}