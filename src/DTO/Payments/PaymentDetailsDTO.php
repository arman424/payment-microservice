<?php

namespace App\DTO\Payments;

class PaymentDetailsDTO
{
    private string $psp;

    private float $amount;

    private string $currency;

    private int $cardNumber;

    private int $cardExpYear;

    private int $cardExpMonth;

    private int $cardCvv;

    public static function init(array $paymentDetails): self
    {
        return (new self())
            ->setPsp($paymentDetails['psp'])
            ->setAmount($paymentDetails['amount'])
            ->setCurrency($paymentDetails['currency'])
            ->setCardNumber($paymentDetails['card_number'])
            ->setCardExpYear($paymentDetails['card_exp_year'])
            ->setCardExpMonth($paymentDetails['card_exp_month'])
            ->setCardCvv($paymentDetails['card_cvv']);
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'card_number' => $this->getCardNumber(),
            'card_exp_year' => $this->getCardExpYear(),
            'card_exp_month' => $this->getCardExpMonth(),
            'card_cvv' => $this->getCardCvv(),
        ];
    }

    public function getPsp(): string
    {
        return $this->psp;
    }

    public function setPsp(string $psp): self
    {
        $this->psp = $psp;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCardNumber(): int
    {
        return $this->cardNumber;
    }

    public function setCardNumber(int $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getCardExpYear(): int
    {
        return $this->cardExpYear;
    }

    public function setCardExpYear(int $cardExpYear): self
    {
        $this->cardExpYear = $cardExpYear;

        return $this;
    }

    public function getCardExpMonth(): int
    {
        return $this->cardExpMonth;
    }

    public function setCardExpMonth(int $cardExpMonth): self
    {
        $this->cardExpMonth = $cardExpMonth;

        return $this;
    }

    public function getCardCvv(): int
    {
        return $this->cardCvv;
    }

    public function setCardCvv(int $cardCvv): self
    {
        $this->cardCvv = $cardCvv;

        return $this;
    }
}