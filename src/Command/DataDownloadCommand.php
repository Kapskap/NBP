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

#[AsCommand(
    name: 'app:data-download',
    description: 'Download exchange rates from www api',
)]
class DataDownloadCommand extends Command
{
    public function __construct(private SourceFactory $sourceFactory)
    {
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

        $result = '';

        $io = new SymfonyStyle($input, $output);

        if($bank == 'nbp'){
            $result = $this->sourceFactory->createObject('NBP');
        }
        elseif($bank == 'floatrates'){
            $result = $this->sourceFactory->createObject('FloatRates');
        }
        $result = $result->getData();

        dd($result);

        $io->success($result);

        return Command::SUCCESS;
    }
}
