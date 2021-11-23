<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class GenderKindType extends AbstractEnumType
{
    public const M = 'M';
    public const F = 'F';

    protected static $choices = [
        self::M => 'Male',
        self::F => 'Female',
    ];
}
