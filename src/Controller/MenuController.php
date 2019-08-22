<?php

namespace App\Controller;

use App\Repository\ProductCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    public function categoryList(ProductCategoryRepository $productCategoryRepository)
    {
        $menuCategories = $productCategoryRepository->findAll();

        return $this->render('menu/menu_list.html.twig',
            ['menuCategories'=>$menuCategories]
        );
    }
}