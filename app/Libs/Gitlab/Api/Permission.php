<?php

namespace App\Libs\Gitlab\Api;


class Permission implements \JsonSerializable
{
    private const PERMISSION_ENUM = [
        0 => 'No access',
        5 => 'Minimal access',
        10 => 'Guest',
        20 => 'Reporter',
        30 => 'Developer',
        40 => 'Maintainer',
        50 => 'Owner',
    ];

    private int $permission;

    private string $permissionName;

    protected function __construct()
    {
    }

    public static function create(int $permissionInteger): static
    {
        $permission = new static();
        $permission->permission = $permissionInteger;
        $permission->permissionName = self::PERMISSION_ENUM[$permissionInteger];

        return $permission;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'permission' => $this->permission,
            'permissionName' => $this->permissionName,
        ];
    }

    /**
     * @return int
     */
    public function getPermission(): int
    {
        return $this->permission;
    }

    /**
     * @return string
     */
    public function getPermissionName(): string
    {
        return $this->permissionName;
    }
}