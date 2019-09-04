<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\FOSRestBundle;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use FOS\RestBundle\Controller\Annotations as Rest;


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
     */
    public function getOne(Product $product): Response
    {

        //dump($product);
        //dump($productJson);
        //dump(json_encode($productJson));
        //$encoder = [new JsonEncoder()];

        //for obj with relations error "A circular reference has been detected when serializing the object"

//        $defaultContext = [
//            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
//
//                if(preg_match("/ProductCategory$/",get_class($object))) {
//                    return $object->getName();
//                }
//            },
//        ];
//        $normalizer = [new ObjectNormalizer(null, null, null, null, null, null, $defaultContext)];
//        //$normalizer = [new ObjectNormalizer()];
//        $serializer = new Serializer($normalizer,$encoder);
//
//        $jsonContent = $serializer->serialize($product,'json');
        $jsonContent = json_encode($product->jsonSerialize());
        return new Response($jsonContent,200);
        //return new Response(json_encode($productJson),200);
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
