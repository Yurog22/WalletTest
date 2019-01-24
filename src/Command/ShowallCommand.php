<?php

namespace App\Command;

use App\Service\WalletService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Walletdb;

class ShowallCommand extends Command
{
    protected static $defaultName = 'showall';

    private $walletService;

    public function __construct(WalletService $walletService)
    {
        Command::__construct();
        $this->walletService = $walletService;
    }


    protected function configure()
    {
        $this
            ->setDescription('Show all currencies in wallet');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $io = new SymfonyStyle($input, $output);

        $obj = $this->walletService->showWalletAll();

        /**
         * @var Walletdb $item
         */
        foreach ($obj as $index => $item) {
            echo $item->getCurrency() . ' ' . $item->getAmount() . "\n";
        }
    }
}
