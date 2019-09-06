<?php

namespace App\Command;
use App\Service\ProductExporterManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportProductsToCsv extends Command
{
    private $exporterManager;
    protected static $defaultName = 'app:export-csv';

    public function __construct(ProductExporterManager $exporterManager)
    {
        $this->exporterManager = $exporterManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Exports data to csv')
             ->setHelp('This command allows you to export specific data to csv file. Example app:export-csv filename optional[id,...]')
             ->addArgument('filename',InputArgument::REQUIRED, 'Name of destination file')
             ->addArgument('ids',InputArgument::IS_ARRAY,'list of items id to export separated with space')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arg = [
            'filename'=> $input->getArgument('filename'),
            'ids'=>$input->getArgument('ids')
        ];

        $output->writeln(['Create file : '.$arg['filename']]);
        $output->writeln(['....'.$this->exporterManager->createCsv($arg['filename'],$arg['ids'])]);
        $output->writeln(['Done']);
        ;
    }
}