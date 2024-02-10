<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\ExchangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Exchange;

#[AsCommand(
    name: 'app:get-data-from-floatrates',
    description: 'get current data from floatrates.com',
)]
class GetDataFromFloatratesCommand extends Command
{
    public function __construct(private ExchangeRepository $exchangeRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Pobieranie danych z serwisu: floatrates.com');
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $jsonContent = file_get_contents("https://www.floatrates.com/daily/pln.json");

        if ($jsonContent != NULL) {
            $data = json_decode($jsonContent, true);

            $sourceId = 2;
            foreach ($data as $rate) {
                $currency = $rate['name'];
                $code = $rate['code'];
                $mid = $rate['rate'];
                $effectiveDate = date("Y-m-d", strtotime($rate['date']));

                $this->exchangeRepository->insertExchange($currency, $code, $mid, $effectiveDate, $sourceId);
            }
            $io->success('Operacja zakończona powodzeniem');
        }
        else{
            $io->success('Operacja zakończona niepowodzeniem !');
        }


        return Command::SUCCESS;
    }
}
