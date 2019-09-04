<?php

namespace App\Controller;

use App\Entity\Product;
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
     * @Rest\Get("/get")
     */
    public function getProd(ProductRepository $productRepository): Response
    {
        $data = $productRepository->createQueryBuilder('p')
            ->select('p.id','p.name')
            ->getQuery()
            ->getResult();
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoders);
        $jsonContent = $serializer->serialize($data,'json');
        return new Response($jsonContent,200);
    }

    /**
     * @Rest\Get("/get/{id}")
     * @param int $id
     */
    public function getOne(ProductRepository $productRepository, int $id): JsonResponse
    {
        $product= $productRepository->find($id);
        if(!$product){
            return new JsonResponse('Resource not found.',404 );
        }
        return new JsonResponse($product->jsonSerialize(),200);
    }

    /**
     * @Route("/set", name="set", methods={"POST"})
     */
    public function setProd(Request $request)
    {
        $requestContent = $request->getContent();
        dump($requestContent);
        dump($request);
        return new Response('done',200);
    }


}
