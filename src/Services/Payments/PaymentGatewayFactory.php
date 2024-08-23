<?php

namespace App\Services\Payments;

use App\Enums\Payments\PaymentMethodsEnum;
use App\Services\Payments\PaymentGateways\AciPaymentGateway;
use App\Services\Payments\PaymentGateways\ShiftPaymentGateway;
use InvalidArgumentException;

readonly class PaymentGatewayFactory
{
    public function __construct(
        private AciPaymentGateway $aciPaymentGateway,
        private ShiftPaymentGateway $shiftPaymentGateway
    ) {
    }

    public function getPaymentMethod(string $paymentMethod): ?object
    {
        return match ($paymentMethod) {
            PaymentMethodsEnum::ACI->value => $this->aciPaymentGateway,
            PaymentMethodsEnum::SHIFT4->value => $this->shiftPaymentGateway,
            default => throw new InvalidArgumentException('Unknown payment method given'),
        };
    }
}