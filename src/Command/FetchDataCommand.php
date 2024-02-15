<?php

namespace App\Command;

use App\Service\GetDataService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fetch-data',
    description: 'get exchange rates from www api',
)]
class FetchDataCommand extends Command
{
    public function __construct(private GetDataService $getdataService)
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

        $result = false;

        $io = new SymfonyStyle($input, $output);

        if($bank == 'nbp'){
            $result = $this->getdataService->getDataFromNBP();
        }
        elseif($bank == 'floatrates'){
            $result = $this->getdataService->getDataFromFloatrates();
        }

        if ($result == true){
            $io->success('Operacja zakończona powodzeniem');
        }
        else {
            $io->success('Operacja zakończona niepowodzeniem');
        }


        return Command::SUCCESS;
    }
}
