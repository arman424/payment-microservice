<?php

namespace App\DTO\Payments;

use DateTimeInterface;

class UnifiedPaymentResponseDTO
{
    private string $transactionId;

    private DateTimeInterface $createdAt;

    private float $amount;

    private string $currency;

    private string $cardBin;

    public static function init(array $response): self
    {
        return (new self())
            ->setTransactionId($response['transaction_id'])
            ->setCreatedAt($response['created_at'])
            ->setAmount($response['amount'])
            ->setCurrency($response['currency'])
            ->setCardBin($response['card_bin']);
    }

    public function toArray(): array
    {
        return [
            'transaction_id' => $this->getTransactionId(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'card_bin' => $this->getCardBin(),
        ];
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

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getCardBin(): string
    {
        return $this->cardBin;
    }

    public function setCardBin(string $cardBin): self
    {
        $this->cardBin = $cardBin;

        return $this;
    }
}
