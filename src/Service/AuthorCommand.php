<?php
namespace App\Service;


use App\Service\DataManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AuthorCommand extends Command
{
    private $dataManager;
    protected static $defaultName = 'app:show:author';

    public function __construct(DataManager $dataManager)
    {
        $this->dataManager = $dataManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Table of authors')
            ->setHelp('Command lists authors')
            ->addOption('char',null, InputOption::VALUE_REQUIRED, 'First letter of author surname')
            ->addOption('save',null, InputOption::VALUE_REQUIRED, 'Name of file')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $letter = $input->getOption('char');
        $filename = $input->getOption('save');
        if($this->dataManager->letterCheck($letter)) {
            $output->writeln('Single letters only.');
        } else {
            $data = $this->dataManager->getData($letter);
            $table = new Table($output);
            $table->setHeaders(['Id', 'Surname', 'Name', 'Books']);
            foreach ($data as $author) {
                $table->addRow($author);
            }
            $table->render();
        }
        if($filename){
            $this->dataManager->createFile($filename,$letter);
            $output->writeln('Saved data in file '.$this->dataManager->getFinalFileName().'.txt');
        }
    }

    public function letterCheck($letter): bool
    {
        return isset($letter) && !preg_match('/^[a-zA-Z]{1}$/',$letter);
    }
}