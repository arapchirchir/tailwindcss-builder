<?php

namespace Tecworld\TailwindBuilder\Support\Enums;

final class BuilderPageStatus
{
    public const DRAFT = 'draft';

    public const PUBLISHED = 'published';

    public const ARCHIVED = 'archived';

    public static function values(): array
    {
        return [
            self::DRAFT,
            self::PUBLISHED,
            self::ARCHIVED,
        ];
    }
}
