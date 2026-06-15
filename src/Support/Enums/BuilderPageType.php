<?php

namespace Tecworld\TailwindBuilder\Support\Enums;

final class BuilderPageType
{
    public const PAGE = 'page';

    public const LANDING = 'landing';

    public static function values(): array
    {
        return [
            self::PAGE,
            self::LANDING,
        ];
    }
}
