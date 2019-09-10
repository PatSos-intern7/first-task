<?php

namespace App\Service;

use App\Entity\ProductCategory;
use App\Repository\ProductCategoryRepository;
use League\Csv\Reader;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;


class CategoryImporterManager
{
    /**
     * @var ProductRepository
     */
    private $categoryRepository;

    private $em;
    private $msg;

    public function __construct(ProductCategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->em = $entityManager;
        $this->msg = ['msq'];
    }

    public function importData($file): void
    {
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            if (!$this->categoryRepository->find($record['id'])) {
                $category = new ProductCategory();
                $category->setName($record['name']);
                $category->setDescription($record['description']);
                $category->setDateOfCreation(new \DateTime($record['dateOfCreation']));
                $category->setDateOfModification(new \DateTime($record['dateOfModification']));

                $productsId = $this->splitProducts($record['products']);
                $productRepository = $this->em->getRepository(Product::class);
                $this->putProductsToCategory($category, $productRepository, $productsId);
                $this->em->persist($category);
                $this->setMsg('Added Category '.$record['name']);
            } else {
                $this->setMsg('Category with id ' . $record['id'] . ' already exists');
            }
        }
        $this->em->flush();
    }

    public function splitProducts(string $products): array
    {
        return explode(',',$products);
    }

    public function putProductsToCategory(ProductCategory $productCategory,ProductRepository $productRepository, array $productsId)
    {
        foreach ($productsId as $id){
            $product  = $productRepository->find($id);
            $productCategory->addProduct($product);
        }
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
}