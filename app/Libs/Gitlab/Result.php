<?php

namespace App\Libs\Gitlab;

class Result implements \JsonSerializable
{
    /**
     * @var User[]
     */
    private array $users;

    /**
     * @param User[] $users
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function jsonSerialize()
    {

    }
}