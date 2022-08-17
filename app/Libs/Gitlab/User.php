<?php

namespace App\Libs\Gitlab;

use Gitlab\Api\Projects;

class User implements \JsonSerializable
{
    private int $id;

    private string $name;

    private string $userName;

    /**
     * @var Group[]
     */
    private array $groups;

    /**
     * @var Project[]
     */
    private array $projects;

    public function __construct(int $id, string $name, string $userName, array $groups = [], array $projects = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->userName = $userName;
        $this->groups = $groups;
        $this->projects = $projects;
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
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @return Group[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param Group[] $groups
     */
    public function setGroups(array $groups): void
    {
        $this->groups = $groups;
    }

    /**
     * @param Group $addGroup
     */
    public function addGroup(Group $addGroup): void
    {
        foreach ($this->groups as $group) {
            if ($group->getId() === $addGroup->getId()) {
                return;
            }
        }

        $this->groups[] = $addGroup;
    }

    /**
     * @return Project[]
     */
    public function getProjects(): array
    {
        return $this->projects;
    }

    /**
     * @param Projects[] $projects
     */
    public function setProjects(array $projects): void
    {
        $this->projects = $projects;
    }

    /**
     * @param Project $addProject
     */
    public function addProject(Project $addProject): void
    {
        foreach ($this->projects as $project) {
            if ($project->getId() === $addProject->getId()) {
                return;
            }
        }

        $this->projects[] = $addProject;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->userName,
            'projects' => $this->getProjects(),
            'groups' => $this->getGroups(),
        ];
    }
}