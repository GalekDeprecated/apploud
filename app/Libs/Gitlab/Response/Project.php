<?php

namespace App\Libs\Gitlab\Response;

class Project implements \JsonSerializable
{
    private int $id;

    private string $name;

    private Permission $permission;

    public static function create(int $id, string $name, Permission $permission): static
    {
        $group = new static();
        $group->id = $id;
        $group->name = $name;
        $group->permission = $permission;

        return $group;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'permissions' => $this->permission->getPermissionName(),
        ];
    }
}