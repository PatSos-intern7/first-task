<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Repository\RepositoryFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ExporterManager
{
    protected $repository;
    protected $collection;
    protected $entityName;
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getData(string $class, array $idList=[])
    {
        $data = $this->em->getRepository('App\Entity\\'.$class);
        if(!empty($idList)){
            return $data->findBy($idList);
        }
        return $data->findAll();
    }

    public function createCsv(string $filename, $class)
    {
        $data = $this->getData($class);
        $result = [];
        foreach ($data as $key => $product) {
            $result[$key]['id'] = $product->getId();
            $result[$key]['name'] = $product->getName();
        }

        $file = fopen($filename.'.csv','w');
        fputcsv($file, ['id','name']);
        foreach ($result as $fff) {
            fputcsv($file,$fff);
        }
        fclose($file);
    }
}