<?php

namespace App\Command;

use App\Service\WalletService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChargeoffCommand extends Command
{
    protected static $defaultName = 'chargeoff';

    private $walletService;

    public function __construct(WalletService $walletService)
    {
        Command::__construct();
        $this->walletService = $walletService;
    }


    protected function configure()
    {
        $this
            ->setDescription('Charge off wallet')
            ->addArgument('currency', InputArgument::OPTIONAL, 'Wallet currency')
            ->addArgument('amount', InputArgument::OPTIONAL, 'Amount to charge off');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $obj = $this->walletService
                ->chargeOffWallet($input->getArgument('currency'), $input->getArgument('amount'));
            echo $obj->getCurrency() . ' ' . $obj->getAmount() . "\n";
        } catch (\RuntimeException $e) {
            echo 'Error: ' . $e->getMessage() . "\n";
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage() . "\n";
        }
    }
}
