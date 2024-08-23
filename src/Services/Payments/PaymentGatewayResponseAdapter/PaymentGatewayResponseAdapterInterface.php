<?php

namespace App\Services\Payments\PaymentGatewayResponseAdapter;

use App\DTO\Payments\UnifiedPaymentResponseDTO;

interface PaymentGatewayResponseAdapterInterface
{
    public function adaptResponse(array $apiResponse): UnifiedPaymentResponseDTO;
}