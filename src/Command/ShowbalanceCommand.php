<?php

namespace App\Command;

use App\Service\WalletService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowbalanceCommand extends Command
{
    protected static $defaultName = 'showbalance';

    private $walletService;

    public function __construct(WalletService $walletService)
    {
        Command::__construct();
        $this->walletService = $walletService;
    }


    protected function configure()
    {
        $this
            ->setDescription('Show balance in defined currency')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Currency');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $destcurrency['name'] = $input->getArgument('arg1');
        try {
            $xml = simplexml_load_file("http://www.cbr.ru/scripts/XML_daily.asp");
            if (!$xml) {
                throw new \Exception();
            }
            $sum = 0;
            foreach ($xml->Valute as $valute) {
                if ($valute->CharCode == $destcurrency['name']) {
                    $destcurrency['nominal'] = (int)$valute->Nominal;
                    $destcurrency['value'] = (float)str_replace(',', '.', $valute->Value);
                }
                if ($this->walletService->getCurrencyAmount($valute->CharCode) != 0) {
                    $sum = $sum + ($this->walletService->getCurrencyAmount($valute->CharCode) / $valute->Nominal * ((float)str_replace(',', '.', $valute->Value)));
                }
            }
            $sum = $sum + $this->walletService->getCurrencyAmount('RUB');
            if ($destcurrency['name'] !== "RUB" and isset($destcurrency['value'])) {
                $sum = $sum * $destcurrency['nominal'] / $destcurrency['value'];
                echo 'Amount of currencies in ' . $destcurrency['name'] . ' is ' . number_format($sum, 2) . "\n";
            } elseif ($destcurrency['name'] === 'RUB') {
                echo 'Amount of currencies in RUB is ' . number_format($sum, 2) . "\n";
            } else {
                echo 'Currency value not found' . "\n";
            }
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage() . "\n";
        }
    }
}
