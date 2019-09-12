<?php
namespace App\Service;

use Psr\Log\LoggerInterface;

class LibraryLogger
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function makeEntry(int $id)
    {
        $this->logger->info("The winner is book number id:".$id);
    }
}