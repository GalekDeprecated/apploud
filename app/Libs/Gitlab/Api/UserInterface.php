<?php

namespace App\Libs\Gitlab\Api;

interface UserInterface extends \JsonSerializable
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @return Group[]
     */
    public function getGroups(): array;

    /**
     * @return Project[]
     */
    public function getProjects(): array;
}