<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\SourceFactory;
use App\Service\Manager\ExchangeManager;

#[AsCommand(
    name: 'app:data-download',
    description: 'Download exchange rates from www api',
)]
class DataDownloadCommand extends Command
{
    public function __construct(private SourceFactory $sourceFactory, private ExchangeManager $exchangeManager)
    {
        $this->exchangeManager = $exchangeManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Podaj źródło danych: nbp lub floatrates')
            ->addArgument('from', InputArgument::REQUIRED, 'Servis api.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            '',
            'Pobieranie danych ze strony serwera:',
        ]);

        $bank = $input->getArgument('from');
        $output->writeln($bank);

//        $result = '';

        $io = new SymfonyStyle($input, $output);

        if ($bank == 'nbp') {
            $result = $this->sourceFactory->createObject('NBP');
            $sourceId = 1;
        } elseif ($bank == 'floatrates') {
            $result = $this->sourceFactory->createObject('FloatRates');
            $sourceId = 2;
        }
        else {
            $result = NULL;
        }
        if ($result != NULL) {
            $result = $result->getData();

            $effectiveDate = $result['effectiveDate'];
            $rates = $result['rates'];

            $check = $this->exchangeManager->checkAndAddData($effectiveDate, $sourceId, $rates);
            if ($check == true) {
                $message = 'Dane zostały pobrane poprawnie';
            } else {
                $message = 'Dane z podaną datą już istnieją';
            }
        }
        else{
            $message = 'Operacja nie powiodła się nalezy podać źródło danych czyli: nbp lub floatrates';
        }

        $io->success($message);

        return Command::SUCCESS;
    }
}
