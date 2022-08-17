<?php

namespace App\Libs\Gitlab;

use App\Libs\Gitlab\Api\Group;
use App\Libs\Gitlab\Api\User;

class Request
{
    private const ACCESS_TOKEN = 'naRAbrD8qPXaXVASQ8Zy';

    private const GITLAB_API_V4 = 'https://gitlab.com/api/v4/';

    private function createRequest(string $uri)
    {
        return new \GuzzleHttp\Psr7\Request('GET', $uri, [
            'PRIVATE-TOKEN' => self::ACCESS_TOKEN,
        ]);
    }

    public function getGroup(int $groupId): \GuzzleHttp\Psr7\Request
    {
        return $this->createRequest('groups/' . $groupId);
    }


    public function getSubGroups(Group $topGroup): \GuzzleHttp\Psr7\Request
    {
        return $this->createRequest('groups/' . $topGroup->getId() . '/subgroups');
    }


    public function getGroupMembers(Group $group): \GuzzleHttp\Psr7\Request
    {
        return $this->createRequest('groups/' . $group->getId() . '/members');
    }


    public function getProjectMembers(int $projectId): \GuzzleHttp\Psr7\Request
    {
        return $this->createRequest('projects/' . $projectId . '/members');
    }
}