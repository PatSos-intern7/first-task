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
    public function addToWishlist(Product $product)
    {
        $session = new Session(new NativeSessionStorage(), new NamespacedAttributeBag());
        $wishlist = $session->all();
        $id = $product->getId();
        if(!isset($wishlist['wish'][$id])) {
            $session->set('wish/' . $id,$id);
        } elseif (count($wishlist['wish'])<=4) {
            $session->set('wish/'.$id, $id);
            $this->addFlash('notice', 'Add to wishlist ID:' . $id);
        } else {
            $this->addFlash('notice', 'You can have maximum 5 products on wishlist');
        }
        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/remove/{id}", name="wishlist_remove")
     */
    public function removeFromWishlist(Product $product)
    {
        $session = new Session(new NativeSessionStorage(), new NamespacedAttributeBag());
        $id = $product->getId();
        $this->addFlash('notice','Removed product from wishlist');
        $session->remove('wish/'.$id, $id);

        return $this->redirectToRoute('product_index');
    }

}
