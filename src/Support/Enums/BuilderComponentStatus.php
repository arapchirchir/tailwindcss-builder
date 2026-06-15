<?php

namespace Tecworld\TailwindBuilder\Support\Enums;

final class BuilderComponentStatus
{
    public const ACTIVE = 'active';

    public const DRAFT = 'draft';

    public const ARCHIVED = 'archived';

    public static function values(): array
    {
        return [
            self::ACTIVE,
            self::DRAFT,
            self::ARCHIVED,
        ];
    }
}
