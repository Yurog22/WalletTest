<?php

namespace App\Service;

use App\Entity\Walletdb;
use Doctrine\ORM\EntityManagerInterface;

class WalletService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function showWalletAll()
    {
        $walletdbRepository = $this->em->getRepository(Walletdb::class);

        return $walletdbRepository->findAll();
    }

    public function chargeWallet($currency, $amount)
    {
        /** @var Walletdb $walletdbResult */
        $walletdbResult = $this->em->getRepository(Walletdb::class)
            ->findOneBy(["currency" => $currency]);
        if (!$walletdbResult) {
            throw new \Exception('Currency not found');
        }
        $walletdbResult->setAmount($walletdbResult->getAmount() + $amount);
        $this->em->persist($walletdbResult);
        $this->em->flush();

        return $this->em->getRepository(Walletdb::class)
            ->findOneBy(["currency" => $currency]);
    }

    public function chargeOffWallet($currency, $amount)
    {
        /** @var Walletdb $walletdbResult */
        $walletdbResult = $this->em->getRepository(Walletdb::class)
            ->findOneBy(["currency" => $currency]);
        if (!$walletdbResult) {
            throw new \Exception('Currency not found');
        }
        if ($amount > $walletdbResult->getAmount()) {
            throw new \RuntimeException('The amount to charge off is more than available');
        }
        $walletdbResult->setAmount($walletdbResult->getAmount() - $amount);
        $this->em->persist($walletdbResult);
        $this->em->flush();

        return $this->em->getRepository(Walletdb::class)
            ->findOneBy(["currency" => $currency]);
    }


    public function getCurrencyAmount($currency)
    {
        if ($this->em->getRepository(Walletdb::class)
                ->findOneBy(["currency" => $currency]) != null) {
            $amount = $this->em->getRepository(Walletdb::class)
                ->findOneBy(["currency" => $currency])->getAmount();
        } else $amount = 0;

        return $amount;
    }
}
