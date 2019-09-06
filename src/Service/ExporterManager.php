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
    protected $entityName;
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function pickSelected(string $class, array $idList=[])
    {
       return $this->em->getRepository('App\Entity\\'.$class)->createQueryBuilder('q')
            ->select('q.id','q.name')->andWhere('q.id IN(:ids)')
            ->setParameter('ids',$idList)
            ->getQuery()
            ->getResult();
    }

    public function getData(string $class, array $idList=[])
    {
        $data = $this->em->getRepository('App\Entity\\'.$class);
        if(!empty($idList[0])){
            return $this->pickSelected($class, $idList);
        }

        return $data->createQueryBuilder('q')
                    ->select('q.id,q.name')
                    ->getQuery()
                    ->getResult();
    }

    public function createCsv(string $filename, $class, $ids)
    {

        $ids = explode(",",$ids);
        $data = $this->getData($class, $ids);
        $result = [];

        foreach ($data as $key => $item) {
            $result[$key]['id'] = $item['id'];
            $result[$key]['name'] = $item['name'];
        }

        $file = fopen($filename.'.csv','w');
        fputcsv($file, ['id','name']);
        foreach ($result as $line) {
            fputcsv($file,$line);
        }
        fclose($file);
    }
}