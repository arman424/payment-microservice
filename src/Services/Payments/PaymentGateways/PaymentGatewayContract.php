<?php

namespace App\Services\Payments\PaymentGateways;

use App\DTO\Payments\PaymentDetailsDTO;
use App\DTO\Payments\UnifiedPaymentResponseDTO;

interface PaymentGatewayContract
{
    public function makePayment(PaymentDetailsDTO $paymentDetailsDTO): UnifiedPaymentResponseDTO;
}