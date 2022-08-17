<?php

declare(strict_types=1);

namespace App\Libs\Gitlab;

use App\Libs\Gitlab\Response\User;

class Response implements \JsonSerializable
{
    /** @var User[] */
    private array $users;

    protected function __construct()
    {
    }

    /**
     * @param User[] $users
     * @return static
     */
    public static function create(array $users): static
    {
        $response = new static();
        $response->users = $users;

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'users' => $this->users,
        ];
    }
}