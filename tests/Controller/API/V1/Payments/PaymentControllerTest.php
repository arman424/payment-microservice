<?php

namespace App\Tests\Controller\API\V1\Payments;

use App\Actions\Payments\MakePaymentAction;
use App\Controller\API\V1\Payments\PaymentController;
use App\DTO\Payments\UnifiedPaymentResponseDTO;
use App\Validator\Payments\PaymentDetailsValidator;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PaymentControllerTest extends TestCase
{
    public function testAPIPaymentSuccess()
    {
        $validatorMock = $this->createMock(PaymentDetailsValidator::class);
        $makePaymentActionMock = $this->createMock(MakePaymentAction::class);

        $responseDTO = UnifiedPaymentResponseDTO::init([
            'transaction_id' => '123456',
            'created_at' => new DateTimeImmutable('2024-08-23 10:44:08.552+0000'),
            'amount' => 100,
            'currency' => 'USD',
            'card_bin' => '411111'
        ]);

        $validatorMock->method('validate')->willReturn($this->createMock(ConstraintViolationListInterface::class));

        $makePaymentActionMock->method('__invoke')->willReturn($responseDTO);

        $controller = new PaymentController($validatorMock, $makePaymentActionMock);

        $request = new Request([], [
            'psp' => 'aci',
            'amount' => '100',
            'currency' => 'USD',
            'card_number' => '4111111111111111',
            'card_exp_year' => '2025',
            'card_exp_month' => '12',
            'card_cvv' => '123',
        ]);

        $response = $controller->makePayment($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode($responseDTO->toArray()),
            $response->getContent()
        );
    }

    public function testAPIPaymentValidationFailed()
    {
        $validatorMock = $this->createMock(PaymentDetailsValidator::class);
        $makePaymentActionMock = $this->createMock(MakePaymentAction::class);

        $violation = new ConstraintViolation(
            'This value is invalid.',
            null,
            [],
            null,
            'field',
            'invalid_value'
        );

        $violationList = new ConstraintViolationList([$violation]);
        $validatorMock->method('validate')->willReturn($violationList);

        $controller = new PaymentController($validatorMock, $makePaymentActionMock);

        $request = new Request([], [
            'psp' => 'SomePSP',
            'amount' => '100',
            'currency' => 'USD',
            'card_number' => '4111111111111111',
            'card_exp_year' => '2025',
            'card_exp_month' => '12',
            'card_cvv' => '123',
        ]);

        $response = $controller->makePayment($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['errors' => ['This value is invalid.']]),
            $response->getContent()
        );
    }

    public function testMakePaymentExceptionHandling()
    {
        $validatorMock = $this->createMock(PaymentDetailsValidator::class);
        $makePaymentActionMock = $this->createMock(MakePaymentAction::class);

        $makePaymentActionMock->method('__invoke')->will(
            $this->throwException(new Exception('Payment failed'))
        );

        $controller = new PaymentController($validatorMock, $makePaymentActionMock);

        $request = new Request([], [
            'psp' => 'shift',
            'amount' => '100',
            'currency' => 'USD',
            'card_number' => '4111111111111111',
            'card_exp_year' => '2025',
            'card_exp_month' => '12',
            'card_cvv' => '123',
        ]);

        $response = $controller->makePayment($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Payment failed']),
            $response->getContent()
        );
    }
}
