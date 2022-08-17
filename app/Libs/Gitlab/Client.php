<?php

namespace App\Libs\Gitlab;


class Client
{
    private const ACCESS_TOKEN = 'naRAbrD8qPXaXVASQ8Zy';

    private \Gitlab\Client $client;

    public function __construct()
    {
        $this->client = new \Gitlab\Client();
        $this->client->authenticate(self::ACCESS_TOKEN, \Gitlab\Client::AUTH_OAUTH_TOKEN);
    }

    /**
     * @param int $topGroupId
     * @return User[]
     */
    public function getUsersByToGroupId(int $topGroupId): array
    {
        $groups = $this->getAllGroups($topGroupId);
        $projects = $this->getAllProjects($groups);
        return $this->getMembers($groups, $projects);
    }

    private function getGroupMembers(int $groupId)
    {
        return $this->client->groups()->members($groupId);
    }

    private function getProjectMembers(int $projectId)
    {
        return $this->client->projects()->members($projectId);
    }

    /**
     * @param array $rawGroups
     * @param array $rawProjects
     * @return User[]
     */
    private function getMembers(array $rawGroups, array $rawProjects): array
    {
        $groupsMembers = $this->getGroupsMembers($rawGroups);
        $projectsMembers = $this->getProjectsMembers($rawProjects);

        /** @var User[] $members */
        $members = [];

        foreach ($groupsMembers as $groupId => $groupsMember) {
            foreach ($groupsMember as $groupMember) {
                $memberId = $groupMember['id'];
                if (!isset($members[$memberId])) {
                    $members[$groupMember['id']] = new User($groupMember['id'], $groupMember['name'], $groupMember['username']);
                }
                $members[$memberId]->addGroup(new Group($groupId, $rawGroups[$groupId]['name'], $groupMember['access_level']));
            }
        }

        foreach ($projectsMembers as $projectId => $projectsMember) {
            foreach ($projectsMember as $projectMember) {
                $memberId = $projectMember['id'];
                if (!isset($members[$projectMember['id']])) {
                    $members[$projectMember['id']] = new User($projectMember['id'], $projectMember['name'], $projectMember['username']);
                }
                $members[$memberId]->addProject(new Project($projectId, $rawProjects[$projectId]['name'], $projectMember['access_level']));

            }
        }

        return $members;
    }

    /**
     * @param array $groups
     * @return array
     */
    private function getGroupsMembers(array $groups): array
    {
        $members = [];
        foreach ($groups as $group) {
            $members[$group['id']] = $this->getGroupMembers($group['id']);
        }

        return $members;
    }

    /**
     * @param array $projects
     * @return array
     */
    private function getProjectsMembers(array $projects): array
    {
        $members = [];
        foreach ($projects as $project) {
            $members[$project['id']] = $this->getProjectMembers($project['id']);
        }

        return $members;
    }

    private function getGroup(int $groupId)
    {
        return $this->client->groups()->show($groupId);
    }

    private function getProject(int $projectId)
    {
        return $this->client->projects()->show($projectId);
    }

    private function getAllProjects(array $groups)
    {
        $projects = [];
        foreach ($groups as $group) {
            foreach ($group['projects'] as $project) {
                $projectId = $project['id'];
                $projects[$projectId] = $this->getProject($projectId);
            }
        }

        return $projects;
    }

    private function getAllGroups(int $topGroupId): array
    {
        $groups = [];
        $groupsIds = [];

        $groupsIds[$topGroupId] = $topGroupId;
        $groupsIds = $this->getAllSubGroupsIds($groupsIds);

        foreach ($groupsIds as $groupId) {
            $groups[$groupId] = $this->getGroup($groupId);
        }

        return $groups;
    }

    /**
     * @param int[] $groupsIds
     * @return int[]
     */
    private function getAllSubGroupsIds(array $groupsIds): array
    {
        foreach ($groupsIds as $groupId) {
            $subGroups = $this->client->groups()->subgroups($groupId);

            if (empty($subGroups)) {
                return $groupsIds;
            }

            foreach ($subGroups as $subGroup) {
                $groupsIds[] = $subGroup['id'];
            }
        }

        return array_unique($this->getAllSubGroupsIds($groupsIds));
    }
}