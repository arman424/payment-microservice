<?php

namespace App\Repository;

use App\DTO\Payments\CreatePaymentDTO;
use App\Entity\Payments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payments>
 */
class PaymentsRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Payments::class);
    }

    public function createPayment(CreatePaymentDTO $createPaymentDTO): Payments
    {
        $payment = new Payments();
        $payment->setTransactionId($createPaymentDTO->getTransactionId());
        $payment->setStatus($createPaymentDTO->getStatus());
        $payment->setAmount($createPaymentDTO->getAmount());
        $payment->setCurrency($createPaymentDTO->getCurrency());
        $payment->setCardBin($createPaymentDTO->getCardBin());
        $payment->setCreatedDate($createPaymentDTO->getCreatedDate());

        $entityManager = $this->getEntityManager();
        $entityManager->persist($payment);
        $entityManager->flush();

        return $payment;
    }
}
