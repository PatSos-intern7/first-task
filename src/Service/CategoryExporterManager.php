<?php

namespace App\Service;


use App\Entity\ProductCategory;
use App\Repository\ProductCategoryRepository;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\DateIntervalNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryExporterManager
{
    /**
     * @var ProductCategoryRepository
     */
    private $categoryRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var Serializer
     */
    private $serial;

    public function __construct(ProductCategoryRepository $categoryRepository, SerializerInterface $serializer)
    {
        $this->categoryRepository = $categoryRepository;
        $this->serializer = $serializer;
        $this->serial = new Serializer([new ObjectNormalizer()],[new CsvEncoder()]);
    }

    public function createCsv($filename,$ids) : void
    {
        if ($ids) {
            $entity = $this->categoryRepository->findBy(['id'=>$ids]);
        } else {
            $entity = $this->categoryRepository->findAll();
        }
        $result = $this->prepData($entity);
        $data = $this->serializer->serialize($result, 'csv');
        $file = fopen($filename.'.csv','w');
        fwrite($file,$data);
        fclose($file);
    }

    public function prepData(array $entity) : array
    {
        $result= [];
        foreach ($entity as $key => $product){
            $result[$key] = $product->csvSerialize();
        }
        return $result;
    }
}