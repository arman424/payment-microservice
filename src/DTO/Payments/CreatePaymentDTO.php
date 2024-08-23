<?php

namespace App\DTO\Payments;

use App\Enums\Payments\PaymentStatusEnum;
use DateTimeImmutable;

class CreatePaymentDTO
{
    private string $transactionId;

    private int $status;

    private DateTimeImmutable $createdDate;

    private float $amount;

    private string $currency;

    private string $cardBin;

    public static function init(array $requestData): self
    {
        return (new self())
            //TODO create a method which will generate transaction ids
            ->setTransactionId('test-transaction-id')
            ->setStatus(PaymentStatusEnum::PENDING->value)
            ->setCreatedDate(new DateTimeImmutable())
            ->setAmount($requestData['amount'])
            ->setCurrency($requestData['currency'])
            ->setCardBin($requestData['card_number']);
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedDate(): DateTimeImmutable
    {
        return $this->createdDate;
    }

    public function setCreatedDate(DateTimeImmutable $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        //TODO consider using Money object for amounts in order to deal with minors.
        // For now I will just cast it to int
        $this->amount = (int) $amount;

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

    public function getCardBin(): string
    {
        return $this->cardBin;
    }

    public function setCardBin(string $cardBin): self
    {
        $this->cardBin = substr($cardBin, 0, 6);

        return $this;
    }
}
