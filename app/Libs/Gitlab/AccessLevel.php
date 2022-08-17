<?php

namespace App\Libs\Gitlab;

interface AccessLevel
{
    public const NO_ACCESS = 0;

    public const MINIMAL_ACCESS = 5;

    public const GUEST = 10;

    public const REPORTER = 20;

    public const DEVELOPER = 30;

    public const MAINTAINER = 40;

    public const OWNER = 50;

    public const ACCESS = [
        self::NO_ACCESS => 'No access',
        self::MINIMAL_ACCESS => 'Minimal access',
        self::GUEST => 'Guest',
        self::REPORTER => 'Reporter',
        self::DEVELOPER => 'Reporter',
        self::MAINTAINER => 'Maintainer',
        self::OWNER => 'Owner'
    ];
}