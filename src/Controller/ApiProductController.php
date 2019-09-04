<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\FOSRestBundle;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
//use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Constraints\Json;


/**
 * @Route("/api/product")
 */

class ApiProductController extends AbstractController
{

    /**
     * @Rest\Get("/")
     */
    public function getProducts(ProductRepository $productRepository): Response
    {
        $data = $productRepository->createQueryBuilder('p')
            ->select('p.id','p.name')
            ->getQuery()
            ->getResult();
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoders);
        $jsonContent = $serializer->serialize($data,'json');
        return new JsonResponse($jsonContent);
    }

    /**
     * @Rest\Get("/{id}")
     * @param int $id
     * @return JsonResponse
     */
    public function getOne(ProductRepository $productRepository, int $id): JsonResponse
    {
        $product= $productRepository->find($id);
        if(!$product){
            return new JsonResponse('Resource not found.',404 );
        }
        return new JsonResponse($product->jsonSerialize());
    }

    /**
     * @Rest\Post("/")
     * @return JsonResponse
     */
    public function addProduct(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product,['csrf_protection'=>false]);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return new JsonResponse('Sucess, product added');
        }
        return new JsonResponse('Resource not found.',404 );
    }

    /**
     * @Rest\Put("/{id}")
     * @param int $id
     * @return JsonResponse
     */
    public function editProduct(ProductRepository $productRepository, Request $request, int $id): JsonResponse
    {
        $product=$productRepository->find($id);
        if(!$product){
            return new JsonResponse('Resource not found.',404 );
        }
        $data = json_decode($request->getContent(),true);
        $form = $this->createForm(ProductType::class,$product,['csrf_protection'=>false]);
        $form->submit($data);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            return new JsonResponse('Edit success');
        }
    }

    /**
     * @Rest\Delete("/{id}")
     * @param int $id
     * @return JsonResponse
     */
    public function deleteProduct(ProductRepository $productRepository, int $id)
    {
        $product = $productRepository->find($id);
        if(!$product){
            return new JsonResponse('Resource not found',404);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();
        return new JsonResponse('Success, product deleted');
    }


}
