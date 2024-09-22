<?php

namespace NarutoRPG\Types;

enum Villages: string
{
    case AME = 'Ame';
    case IWA = 'Iwa';
    case KONOHA = 'Konoha';
    case KUMO = 'Kumo';
    case KUSA = 'Kusa';
    case SUNA = 'Suna';
    case TAKI = 'Taki';

    /**
     * @return array<string>
     */
    public static function all(): array
    {
        return [
            self::AME->value,
            self::IWA->value,
            self::KONOHA->value,
            self::KUMO->value,
            self::KUSA->value,
            self::SUNA->value,
            self::TAKI->value,
        ];
    }
}
