<?php

namespace App\Services\Payments\PaymentGatewayResponseAdapter;

use App\DTO\Payments\UnifiedPaymentResponseDTO;
use DateTimeImmutable;
use Exception;

readonly class ShiftPaymentGatewayResponseAdapter implements PaymentGatewayResponseAdapterInterface
{
    public function __construct(
        private UnifiedPaymentResponseDTO $unifiedPaymentResponseDTO
    ) {
    }

    /**
     * @throws Exception
     */
    public function adaptResponse(array $apiResponse): UnifiedPaymentResponseDTO
    {
        return $this->unifiedPaymentResponseDTO::init([
                'transaction_id' => $apiResponse['id'],
                'created_at' => new DateTimeImmutable($apiResponse['created']),
                'amount' => $apiResponse['amount'],
                'currency' => $apiResponse['currency'],
                'card_bin' => $apiResponse['card']['first6'],
            ]
        );
    }
}