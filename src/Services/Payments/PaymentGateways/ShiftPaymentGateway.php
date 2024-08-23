<?php

namespace App\Services\Payments\PaymentGateways;

use App\DTO\Payments\PaymentDetailsDTO;
use App\DTO\Payments\UnifiedPaymentResponseDTO;
use App\Services\HttpClient\HttpClientServiceContract;
use App\Services\Payments\PaymentGatewayResponseAdapter\PaymentGatewayResponseAdapterInterface;

class ShiftPaymentGateway implements PaymentGatewayContract
{
    private string $apiUrl;

    private string $apiKey;

    public function __construct(
        private HttpClientServiceContract $httpClient,
        private PaymentGatewayResponseAdapterInterface $responseAdapter,
    ) {
        $this->apiUrl = getenv('SHIFT_API_URL');
        $this->apiKey = getenv('SHIFT_API_KEY');
    }

    public function makePayment(PaymentDetailsDTO $paymentDetailsDTO): UnifiedPaymentResponseDTO
    {
        //TODO implement the real request
//        $response = $this->httpClient->post(
//            $this->apiUrl,
//            $paymentDetailsDTO->toArray(),
//            [
//                'Authorization' => "Bearer ". $this->apiKey,
//            ]
//        )->toArray();

        $response = [
            'id' => 'test-transaction-id',
            'amount' => $paymentDetailsDTO->getAmount(),
            'currency' => $paymentDetailsDTO->getCurrency(),
            'description' => 'Example charge',
            'card' => [
                'first6' => substr($paymentDetailsDTO->getCardNumber(), 0, 6),
                'last4' => substr($paymentDetailsDTO->getCardNumber(), 11, 4),
                'expMonth' => $paymentDetailsDTO->getCardExpMonth(),
                'expYear' => $paymentDetailsDTO->getCardExpYear(),
                'cardholderName' => 'John Doe',
            ],
            "created" => '2024-08-23 10:44:08.552+0000',
            'captured' => true,
            'refunded' => false,
            'disputed' => false,
        ];

        return $this->responseAdapter->adaptResponse($response);
    }
}