<?php

namespace App\Tests\Command\Payments;

use App\Actions\Payments\MakePaymentAction;
use App\Command\Payments\MakePaymentCommand;
use App\DTO\Payments\UnifiedPaymentResponseDTO;
use App\Validator\Payments\PaymentDetailsValidator;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class MakePaymentCommandTest extends KernelTestCase
{
    public function testCommandPaymentSuccess()
    {
        $validatorMock = $this->createMock(PaymentDetailsValidator::class);
        $makePaymentActionMock = $this->createMock(MakePaymentAction::class);

        $violationsMock = $this->createMock(ConstraintViolationListInterface::class);
        $violationsMock->method('count')->willReturn(0); // Simulate no violations

        $validatorMock->method('validate')->willReturn($violationsMock);

        $responseDTO = UnifiedPaymentResponseDTO::init([
            'transaction_id' => '123456',
            'created_at' => new DateTimeImmutable('2024-08-23 10:44:08.552+0000'),
            'amount' => 100,
            'currency' => 'USD',
            'card_bin' => '411111'
        ]);

        $makePaymentActionMock->method('__invoke')->willReturn($responseDTO);

        $command = new MakePaymentCommand($validatorMock, $makePaymentActionMock);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'psp' => 'aci',
            '--amount' => '100',
            '--currency' => 'USD',
            '--card-number' => '4111111111111111',
            '--card-exp-year' => '2024',
            '--card-exp-month' => '12',
            '--card-cvv' => '123',
        ]);

        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('Payment request has been processed.', $commandTester->getDisplay());
    }

    public function testCommandPaymentValidationFailed()
    {
        self::bootKernel();

        $container = self::getContainer();
        $validator = $container->get(PaymentDetailsValidator::class);
        $makePaymentAction = $container->get(MakePaymentAction::class);

        $command = new MakePaymentCommand($validator, $makePaymentAction);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'psp' => 'SomePSP',
            '--amount' => '100',
            '--currency' => 'USD',
            '--card-number' => '4111111111111111',
            '--card-exp-year' => '2025',
            '--card-exp-month' => '12',
            '--card-cvv' => '123',
        ]);

        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
        $this->assertStringContainsString('[ERROR] Invalid PSP value.', $commandTester->getDisplay());
    }
}
