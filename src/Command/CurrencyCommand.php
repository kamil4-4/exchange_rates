<?php

namespace App\Command;

use App\Service\CurrencyService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:currency',
    description: 'Adding and updating currencies',
)]
class CurrencyCommand extends Command
{
    public function __construct(private CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = "http://api.nbp.pl/api/exchangerates/tables/A/";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $rates = json_decode(curl_exec($curl), true)[0]['rates'];

        $this->currencyService->createOrUpdateCurrencyArray($rates);
        
        return Command::SUCCESS;
    }
}
