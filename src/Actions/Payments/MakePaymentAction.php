<?php

namespace App\Actions\Payments;

use App\DTO\Payments\CreatePaymentDTO;
use App\DTO\Payments\PaymentDetailsDTO;
use App\DTO\Payments\UnifiedPaymentResponseDTO;
use App\Repository\PaymentsRepository;
use App\Services\Payments\PaymentGatewayFactory;
use Exception;
use Psr\Log\LoggerInterface;

readonly class MakePaymentAction
{
    public function __construct(
        private PaymentsRepository $paymentsRepository,
        private PaymentGatewayFactory $paymentGatewayFactory,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(PaymentDetailsDTO $paymentDetailsDTO): UnifiedPaymentResponseDTO
    {
        try {
            $this->paymentsRepository->createPayment(CreatePaymentDTO::init($paymentDetailsDTO->toArray()));

            $paymentGateway = $this->paymentGatewayFactory->getPaymentMethod($paymentDetailsDTO->getPsp());

            return $paymentGateway->makePayment($paymentDetailsDTO);
        } catch (Exception $exception) {
            $this->logger->error('Payment failed', [
                'psp' => $paymentDetailsDTO->getPsp(),
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);

            //TODO can be created a custom payment exception
            throw new Exception('Payment processing failed.');
        }
    }
}