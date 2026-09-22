<?php

namespace App\Enums;

enum ReferenceType: string
{
    case REQ = 'REQ';
    case PRJ = 'PRJ';
    case REC = 'REC';

    /**
     * Get all supported reference type values.
     *
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
