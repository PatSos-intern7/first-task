<?php

namespace App\Command;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ExporterManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportToCsv extends Command
{
    private $exporterManager;
    protected static $defaultName = 'app:export-csv';

    public function __construct(ExporterManager $exporterManager)
    {
        $this->exporterManager = $exporterManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Exports data to csv')
             ->setHelp('This command allows you to export specific data to csv file. Example app:export-csv filename class-name optional[id,...]')
             ->addArgument('filename',InputArgument::REQUIRED, 'Name of destination file')
             ->addArgument('class',InputArgument::REQUIRED,'Name class export from')
             ->addArgument('ids',InputArgument::OPTIONAL,'list of items id to export separated with "," (colon)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arg = [
            'filename'=> $input->getArgument('filename'),
            'class'=>$input->getArgument('class'),
            'ids'=>$input->getArgument('ids')
        ];

        $output->writeln(['Create file : '.$arg['filename']]);
        $output->writeln(['for class  '.$arg['class']]);
        $output->writeln(['....'.$this->exporterManager->createCsv($arg['filename'],$arg['class'],$arg['ids'])]);
        $output->writeln(['Done']);
        ;
    }
}