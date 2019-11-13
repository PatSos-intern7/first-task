<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookGenreRepository;
use App\Repository\BookRepository;
use App\Repository\ProductCategoryRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function authorList(AuthorRepository $authorRepository)
    {
        $authorMenu = $this->getDropdownList($authorRepository,5);

        return $this->render('menu/book-menu-list.html.twig',
            ['authorMenu'=>$authorMenu]
        );
    }

    public function bookList(BookRepository $bookRepository)
    {
        $menu = $this->getDropdownList($bookRepository,5);
        return $this->render('menu/book-menu-list.html.twig',
            ['bookMenu'=>$menu]
        );
    }

    public function bookGenreList(BookGenreRepository $bookGenreRepository)
    {
        $menu = $this->getDropdownList($bookGenreRepository,0);
        return $this->render('menu/book-menu-list.html.twig',
            ['bookGenreMenu'=>$menu]
        );
    }

    public function getDropdownList(ServiceEntityRepository $serviceEntityRepository, int $limit)
    {
        if($limit === 0){
            $list = $serviceEntityRepository->findAll();
        } else {
            $list = $serviceEntityRepository->findBy([], null, $limit);
        }
        return $list;
    }
}