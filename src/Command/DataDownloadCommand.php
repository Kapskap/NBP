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
        private RateDTO $rateDTO
    )
    {
        $this->exchangeManager = $exchangeManager;
        $this->exchangeDTO = $exchangeDTO;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->text('Witaj w serwisie pobierającym dane dotyczące kursu wymiany walut.');
        $sources = $this->getSource();
        $sourceChoice = $io->choice('Wybierz źródło danych', $sources);

        $output->writeln('Pobieranie danych ze strony serwera:');
        $output->writeln([$sourceChoice,'']);

        $resultObject = $this->sourceFactory->createObject($sourceChoice);

        $result = $resultObject->getData(); //Pobieranie danych

        $effectiveDate = $result['effectiveDate'];
        $sourceId = $result['sourceId'];
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

    protected function getSource(): array
    {
        return [
            'Narodowy Bank Polski',
            'Float Rates',
            'Coin Cap'
        ];
    }
}
