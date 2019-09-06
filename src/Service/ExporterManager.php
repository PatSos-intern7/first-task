<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\Serializer\SerializerInterface;

class ExporterManager
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(ProductRepository $productRepository, SerializerInterface $serializer)
    {
        $this->productRepository = $productRepository;
        $this->serializer = $serializer;
    }

    public function createCsv($filename,$ids)
    {
        if ($ids) {
            $data = $this->serializer->serialize($this->productRepository->findBy(['id' => $ids]), 'csv');
        } else {
            $data = $this->serializer->serialize($this->productRepository->findAll(), 'csv');
        }
        $file = fopen($filename.'.csv','w');
        fwrite($file,$data);
        fclose($file);
    }

}