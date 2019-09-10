<?php

namespace App\Command;
use App\Service\CategoryImporterManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCategory extends Command
{
    private $importerManager;
    protected static $defaultName = 'app:category:import-csv';

    public function __construct(CategoryImporterManager $importerManager)
    {
        $this->importerManager = $importerManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Exports data to csv')
            ->setHelp('This command allows you to export specific data to csv file. Example app:export-csv filename optional[id,...]')
            ->addArgument('filename',InputArgument::REQUIRED, 'Name of source file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arg = [
            'filename'=> $input->getArgument('filename'),
        ];

        $output->writeln(['Import products: '.$arg['filename']]);
        $output->writeln(['....'.$this->importerManager->importData($arg['filename'])]);
        $output->writeln(['....'.$this->importerManager->getMsg()]);
        $output->writeln(['Done']);
        ;
    }
}