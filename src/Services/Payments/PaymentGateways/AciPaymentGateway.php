<?php

namespace App\Services\Payments\PaymentGateways;

use App\DTO\Payments\PaymentDetailsDTO;
use App\DTO\Payments\UnifiedPaymentResponseDTO;
use App\Services\HttpClient\HttpClientServiceContract;
use App\Services\Payments\PaymentGatewayResponseAdapter\PaymentGatewayResponseAdapterInterface;

readonly class AciPaymentGateway implements PaymentGatewayContract
{
    private string $apiUrl;

    private string $apiKey;

    public function __construct(
        private HttpClientServiceContract $httpClient,
        private PaymentGatewayResponseAdapterInterface $responseAdapter,
    ) {
        $this->apiUrl = $_ENV['ACI_API_URL'];
        $this->apiKey = $_ENV['ACI_API_KEY'];
    }

    /**
     */
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
            'resultCode' => '000.100.110',
            'resultDescription' => "Request successfully processed in 'Merchant in Integrator Test Mode'",
            'cardBin' => substr($paymentDetailsDTO->getCardNumber(), 0, 6),
            'cardLast4' => '0000',
            'expiryMonth' => $paymentDetailsDTO->getCardExpMonth(),
            'expiryYear' => $paymentDetailsDTO->getCardExpYear(),
            'timestamp' => '2024-08-23 10:44:08.552+0000',
        ];

        return $this->responseAdapter->adaptResponse($response);
    }
}