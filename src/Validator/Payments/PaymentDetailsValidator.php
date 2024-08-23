<?php

namespace App\Validator\Payments;

use App\Enums\Payments\PaymentMethodsEnum;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class PaymentDetailsValidator
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function validate(array $data): ConstraintViolationListInterface
    {
        $constraints = new Assert\Collection([
            'psp' => [
                new Assert\NotBlank(),
                new Assert\Callback([$this, 'validatePsp']),
            ],
            'amount' => [
                new Assert\NotBlank(),
                new Assert\Positive(),
            ],
            'currency' => [
                new Assert\NotBlank(),
                new Assert\Currency(),
            ],
            'card_number' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 16, 'max' => 16]),
                new Assert\Type('digit'),
            ],
            'card_exp_year' => [
                new Assert\NotBlank(),
                new Assert\GreaterThanOrEqual(date('Y')),
            ],
            'card_exp_month' => [
                new Assert\NotBlank(),
                new Assert\Range(['min' => 1, 'max' => 12]),
            ],
            'card_cvv' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 3, 'max' => 4]),
            ],
        ]);

        return $this->validator->validate($data, $constraints);
    }

    //TODO can be created a custom Constraint class
    public function validatePsp($value, ExecutionContextInterface $context): void
    {
        if (!in_array($value, PaymentMethodsEnum::getValues(), true)) {
            $context->buildViolation('Invalid PSP value.')
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
