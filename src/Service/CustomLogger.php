<?php
namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CustomLogger
{
    private $logger;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var Request|null
     */
    private $currentRequest;
    private $route;
    /**
     * @var string|null
     */
    private $remoteIp;
    private $prodId;

    public function __construct(LoggerInterface $logger, RequestStack $requestStack)
    {
        $this->logger = $logger;
        $this->requestStack = $requestStack;
        $this->currentRequest = $this->requestStack->getCurrentRequest();
        $this->route = $this->getRoute();
        $this->remoteIp = $this->getRemoteIp();
        $this->prodId = $this->getId();
    }

    public function getLogger()
    {
        $this->logger->info('info');
        return $this;
    }

    public function getId()
    {
        return $this->currentRequest->get('id');
    }

    public function getRoute()
    {
       return $this->currentRequest->get('_route');
    }

    public function getRequestStack()
    {
        return $this->requestStack;
    }

    public function getRemoteIp()
    {
        return $this->currentRequest->getClientIp();
    }

    public function makeEntry()
    {
        $this->logger->warning("DDProduct with id: ".$this->getId()." was ".$this->route." by user:".$this->getRemoteIp());
    }
}