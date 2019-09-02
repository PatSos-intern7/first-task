<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wishlist")
 */
class WishlistController extends AbstractController
{
    /**
     * @Route("/add/{id}", name ="wishlist_add")
     */
    public function addToWishlist(Product $product, Session $session)
    {
        $session->set('wishlist',$product->getId());

        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/remove/{id}", name="wishlist_remove")
     */
    public function removeFromWishlist(Product $product, Session $session)
    {
        $session->remove('wishlist');
        return $this->redirectToRoute('product_index');
    }
}
