<?php

namespace App\Enums\Payments;

enum PaymentMethodsEnum: string
{
    case ACI = 'aci';
    case SHIFT4 = 'shift4';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
