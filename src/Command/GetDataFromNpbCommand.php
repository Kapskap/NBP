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
use App\Service\LanguageService;


#[AsCommand(
    name: 'app:get-data-from-nbp',
    description: 'get current data from nbp',
)]
class GetDataFromNpbCommand extends Command
{

    public function __construct(private LanguageService $languageService, private ExchangeRepository $exchangeRepository)
    {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setDescription('Pobieranie danych z Narodowego Banku Polskiego')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $jsonContent = file_get_contents("https://api.nbp.pl/api/exchangerates/tables/A/?format=json");

        if ($jsonContent != NULL) {
            $data = json_decode($jsonContent, true);

            foreach ($data as $array) {
                $effectiveDate = $array['effectiveDate'];
                $rates = $array['rates'];
            }

            $sourceId = 1;
            foreach ($rates as $rate) {
                $code = $rate['code'];
                $mid = $rate['mid'];

//                $languageId = $this->languageService->checkCode($code);
//dd($languageId);
//                if ($languageId == NULL) {
//                    throw $this->createNotFoundException('Nie znaleziono waluty o oznaczeniu '.$code);
//                }

                $this->exchangeRepository->insertExchange($code, $mid, $effectiveDate, $sourceId);
            }
            $io->success('Operacja zakończona powodzeniem');
        }
        else{
            $io->success('Operacja zakończona niepowodzeniem !');
        }

        return Command::SUCCESS;
    }
}
