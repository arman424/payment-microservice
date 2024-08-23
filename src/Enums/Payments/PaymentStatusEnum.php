<?php

namespace App\Enums\Payments;

enum PaymentStatusEnum: int
{
    case PENDING = 1;

    case SUCCESS = 2;

    case FAILED = 3;
}