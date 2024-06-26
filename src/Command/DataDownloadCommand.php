<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\SourceFactory;
use App\Service\Manager\ExchangeManager;
use App\Service\Dto\ExchangeDTO;
use App\Service\Dto\RateDTO;
use App\Service\SourceService;

#[AsCommand(
    name: 'app:data-download',
    description: 'Download exchange rates from www api',
)]
class DataDownloadCommand extends Command
{
    public function __construct(
        private SourceFactory $sourceFactory,
        private ExchangeManager $exchangeManager,
        private ExchangeDTO $exchangeDTO,
        private RateDTO $rateDTO,
        private SourceService $sourceService
    )
    {
        $this->exchangeManager = $exchangeManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->text('Witaj w serwisie pobierającym dane dotyczące kursu wymiany walut.');
        $sourcesName = $this->sourceService->getSourceName();
        $sourceName = $io->choice('Wybierz źródło danych', $sourcesName);

        $output->writeln('Pobieranie danych ze strony serwera:');
        $output->writeln([$sourceName,'']);

        $sourceId = $this->sourceService->getSourceIdByName($sourceName);
        $resultObject = $this->sourceFactory->createObject($sourceName);

        $result = $resultObject->getData(); //Pobieranie danych

        $effectiveDate = $result['effectiveDate'];
        $rates = $result['rates'];
        $midCode = $result['midCode'];

        $this->exchangeDTO->setDTO($effectiveDate, $sourceId, $rates, $midCode); //Zamiana tablicy na obiekt DTO

        $check = $this->exchangeManager->AddData($this->exchangeDTO); //Dodawanie danych do bazy

        if ($check == true) {
            $io->success('Dane zostały pobrane poprawnie');
        } else {
            $io->note('Dane z podaną datą już istnieją');
        }

        return Command::SUCCESS;
    }

}
