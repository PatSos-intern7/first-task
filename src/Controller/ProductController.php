<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        $session = new Session(new NativeSessionStorage(), new NamespacedAttributeBag());
        //$session->clear();
        //$session->start();
        $wishlist = $session->all();
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'wishlist'=>$wishlist,
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageMain = $form['Image']->getData();
            if($imageMain) {
                $imageFileName = $fileUploader->upload($imageMain);
                $product->setImage($imageFileName);
            }
            $imageGallery = $form['imageGallery']->getData();
            $entityManager = $this->getDoctrine()->getManager();
            if(is_array($imageGallery)) {
                foreach($imageGallery as $singleFile) {
                    $image = new Image();
                    $image->setPath($fileUploader->getTargetDirectory());
                    $image->setName($fileUploader->upload($singleFile));
                    $product->addImageGallery($image);
                    $entityManager->persist($image);
                }
            }
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('notice','created product ID: '.$product->getId());

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $imageFile = $form['image']->getData();
//            if($imageFile) {
//                $imageFileName = $fileUploader->upload($imageFile);
//                $product->setImage($imageFileName);
//            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productId = $product->getId();
            $entityManager = $this->getDoctrine()->getManager();
            $session = new Session(new NativeSessionStorage(), new NamespacedAttributeBag());
            $session->remove('wish/'.$productId, $productId);
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('notice','Removed product from wishlist');
            $this->addFlash('notice','Deleted product with ID:'.$productId);
        }

        return $this->redirectToRoute('product_index');
    }


}
