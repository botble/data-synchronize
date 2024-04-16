<?php

namespace Botble\DataSynchronize\Enums;

use Botble\Base\Supports\Enum;

class ExportColumnType extends Enum
{
    public const DROPDOWN = 'dropdown';

    public const TEXT = 'text';

    public const NUMBER = 'number';

    public const DATETIME = 'datetime';

    public const BOOLEAN = 'boolean';
}
