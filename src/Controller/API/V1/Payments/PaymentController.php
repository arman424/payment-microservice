<?php

namespace App\Controller\API\V1\Payments;

use App\Actions\Payments\MakePaymentAction;
use App\DTO\Payments\PaymentDetailsDTO;
use App\Validator\Payments\PaymentDetailsValidator;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class PaymentController
{
    public function __construct(
        private PaymentDetailsValidator $validator,
        private MakePaymentAction $makePaymentAction
    ) {}

    // TODO: Add Swagger annotations for API documentation
    public function makePayment(Request $request, string $paymentType): JsonResponse
    {
        $paymentData = array_merge($request->request->all(), ['psp' => $paymentType]);

        $violations = $this->validator->validate($paymentData);

        if ($violations->count() > 0) {
            $errors = array_map(fn($violation) => $violation->getMessage(), $violations->getIterator()->getArrayCopy());
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        try {
            $response = ($this->makePaymentAction)(PaymentDetailsDTO::init($paymentData));
            return new JsonResponse($response->toArray(), Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}