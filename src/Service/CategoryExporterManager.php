<?php

namespace App\Service;


use App\Repository\ProductCategoryRepository;
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

    public function __construct(ProductCategoryRepository $categoryRepository, SerializerInterface $serializer)
    {
        $this->categoryRepository = $categoryRepository;
        $this->serializer = $serializer;
    }

    public function createCsv($filename,$ids)
    {
        if ($ids) {
            $data = $this->serializer->serialize($this->categoryRepository->findBy(['id' => $ids]), 'csv');
        } else {
            $data = $this->serializer->serialize($this->categoryRepository->findAll(), 'csv');
        }
        $file = fopen($filename.'.csv','w');
        fwrite($file,$data);
        fclose($file);
    }

}