<?php

namespace Tecworld\TailwindBuilder\Support\Enums;

final class BuilderComponentType
{
    public const HEADER = 'header';

    public const FOOTER = 'footer';

    public const SECTION = 'section';

    public const ELEMENT = 'element';

    public const GLOBAL = 'global';

    public static function values(): array
    {
        return [
            self::HEADER,
            self::FOOTER,
            self::SECTION,
            self::ELEMENT,
            self::GLOBAL,
        ];
    }
}
