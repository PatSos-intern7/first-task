<?php

namespace App\Service;

use App\Entity\ProductCategory;
use App\Repository\ProductCategoryRepository;
use League\Csv\Reader;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;


class ProductImporterManager
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    private $em;
    private $msg;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager)
    {
        $this->productRepository = $productRepository;
        $this->em = $entityManager;
        $this->msg = [];
    }

    public function importData($file): void
    {
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record){
            $proxyProduct = $this->productRepository->find($record['id']);
            $categoryRepository = $this->em->getRepository(ProductCategory::class);
            $category  = $categoryRepository->find($record['category.id']);

            if(!$proxyProduct) {
                $product = new Product();
                $this->productSave($category, $product, $record);
            } else {
                $this->productSave($category, $proxyProduct, $record);
            }
        }
        $this->em->flush();
    }

    public function setMsg(string $msg): void
    {
        $this->msg[] = $msg;
    }

    public function getMsg(): string
    {
        $result = "";
        foreach ($this->msg as $msg){
            $result .= $msg."\n";
        }
        return $result;
    }

    /**
     * @param ProductCategory|null $category
     * @param Product $product
     * @param $record
     */
    private function productSave(?ProductCategory $category, Product $product, $record): void
    {
        $product->dataFromArray($record);
        if ($category) {
            $product->setCategory($category);
            $this->em->persist($product);
            $this->setMsg('Ok, product ' . $record['id'] . ' saved');
        } else {
            $this->setMsg('Incorrect category with id ' . $record['category.id'] . '. Product with id ' . $record['id'] . ' skipped ');
        }
    }


}