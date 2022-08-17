<?php

namespace App\Libs\Gitlab;

use App\Libs\Gitlab\Api\Group;
use App\Libs\Gitlab\Api\Project;
use App\Libs\Gitlab\Api\User;
use App\Libs\Gitlab\Response\Permission;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class Client
{
    private const ACCESS_TOKEN = 'naRAbrD8qPXaXVASQ8Zy';

    private const GITLAB_API_V4 = 'https://gitlab.com/api/v4/';

    private GuzzleClient $client;

    /**
     * @var Group[]
     */
    private array $groups;

    /**
     * @var Group\Member[]
     */
    private array $members;

    private array $projects;

    /**
     * @var Request
     */
    private Request $request;

    public function __construct()
    {
        $this->client = new GuzzleClient([
            'base_uri' => self::GITLAB_API_V4,
        ]);
        $this->request = new Request();
    }

    private function sendRequest(GuzzleRequest $request): array
    {
        $response = $this->client->send($request);

        return json_decode((string)$response->getBody(), true);
    }

    public function getGroup(int $groupId): Group
    {
        $request = $this->request->getGroup($groupId);
        $response = $this->sendRequest($request);

        $group = Group::create($response);
        $this->groups[$group->getId()] = $group;

        $members = $this->getGroupMembers($group);
        $group->setMembers($members);

        return $group;
    }

    /**
     * @param Group $topGroup
     * @return Group[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSubGroups(Group $topGroup): array
    {
        $request = $this->request->getSubGroups($topGroup);
        $responseData = $this->sendRequest($request);

        if (empty($responseData)) {
            return [];
        }

        $groups = [];
        foreach ($responseData as $subGroup) {
            $group = $this->getGroup($subGroup['id']);

            $subGroups = $this->getSubGroups($group);
            $groups[$group->getId()] = $group;

            if (!empty($subGroups)) {
                $groups = array_merge($groups, $subGroups);
            }
        }

        return $groups;
    }

    /**
     * @param Group $group
     * @return User[]
     */
    public function getGroupMembers(Group $group): array
    {
        $request = $this->request->getGroupMembers($group);
        $responseMembers = $this->sendRequest($request);
        $members = [];

        foreach ($responseMembers as $responseMember) {
            $user = Group\Member::create($responseMember);
            $members[$user->getId()] = $user;
            $this->members[$user->getId()] = $user;
        }

        bdump($members);
        $group->setMembers($members);

        return $members;
    }

    /**
     * @param int $projectId
     * @return User[]
     */
    public function getProjectMembers(int $projectId): array
    {
        $request = $this->request->getProjectMembers($projectId);
        $responseMembers = $this->sendRequest($request);

        $members = [];
        foreach ($responseMembers as $responseMember) {
            $user = User::create($responseMember);
            $members[$user->getId()] = $user;
        }

        return $members;
    }

    public function getAccesses($topGroupId)
    {
        bdump('---TOP---');
        $topGroup = $this->getGroup($topGroupId);
        bdump('/---TOP---');

        $groupMembers = $this->getGroupMembers($topGroup);
        $subGroups = $this->getSubGroups($topGroup);
        bdump($topGroup);
        bdump($subGroups);

        bdump($this->groups);
        bdump($this->members);

        /*foreach ($this->members as $member) {
            $this->getUserMemberShips($member->getId());
        }*/

        $groups = array_merge([$topGroup], $subGroups);


        /** @var Project[] $projects */
        $projects = [];

        foreach ($groups as $group) {
            $projects = array_merge($group->getProjects(), $projects);
        }

        $projectMembers = [];
        foreach ($projects as $project) {
            $projectMembers[$project->getId()] = $this->getProjectMembers($project->getId());
        }

        $usersInfo = [];
        foreach ($projectMembers as $projectMember) {
            foreach ($projectMember as $projectMember2) {
                $usersInfo[$projectMember2->getId()] = $projectMember2;
            }
        }

        foreach ($groupMembers as $groupMember) {
            $userInfo[$groupMember->getId()] = $groupMember;
        }


        $users = [];
        foreach ($usersInfo as $userInfo) {
            $userProjects = [];
            foreach ($projects as $project) {
                if ($project->getCreatorId() === $userInfo->getId()) {
                    $userProjects[] = \App\Libs\Gitlab\Response\Project::create(
                        $project->getId(),
                        $project->getName(),
                        Permission::create(0)
                    );
                }
            }
            $users[] = \App\Libs\Gitlab\Response\User::create(
                $userInfo->getName(),
                $userInfo->getUsername(),
                [],
                $userProjects
            );
        }

        $response = Response::create($users);

        return $projectMembers;
    }

    public function test(int $topGroupId)
    {
        $client = new \Gitlab\Client();
        $client->authenticate(self::ACCESS_TOKEN, \Gitlab\Client::AUTH_OAUTH_TOKEN);

        $topGroup = $client->groups()->show($topGroupId);
        $topGroupMembers = $client->groups()->members($topGroupId);
        $subGroups = $client->groups()->subgroups($topGroupId);
        bdump($topGroup);
        bdump($topGroupMembers);
        bdump($subGroups);

    }
}