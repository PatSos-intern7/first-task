<?php

namespace App\Controller;

use App\Entity\ProductCategory;
use App\Form\ProductCategoryType;
use App\Repository\ProductCategoryRepository;
use Entity\Repository\CategoryRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\FOSRestBundle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Constraints\Json;


/**
 * @Route("/api/category")
 */

class ApiCategoryController extends AbstractController
{

    /**
     * @Rest\Get("/")
     */
    public function getCategories(ProductCategoryRepository $productCategoryRepository)
    {
        $data = $productCategoryRepository->createQueryBuilder('p')
            ->select('p.id','p.name')
            ->getQuery()
            ->getResult();
        return new JsonResponse($data);
    }

    /**
     * @Rest\Get("/{id}")
     * @param int $id
     * @return JsonResponse
     */
    public function getOne(ProductCategoryRepository $productCategoryRepository, int $id): JsonResponse
    {
        $category= $productCategoryRepository->find($id);
        if(!$category){
            return new JsonResponse('Resource not found.',404 );
        }
        //dump($category->jsonSerialize());exit;
        return new JsonResponse($category->jsonSerialize());
    }

    /**
     * @Rest\Post("/")
     * @return JsonResponse
     */
    public function addCategory(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $category = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $category,['csrf_protection'=>false]);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return new JsonResponse('Sucess, category added');
        }
        return new JsonResponse('Resource not found.',404 );
    }

    /**
     * @Rest\Put("/{id}")
     * @param int $id
     * @return JsonResponse
     */
    public function editCategory(ProductCategoryRepository $productCategoryRepository, Request $request, int $id): JsonResponse
    {
        $category=$productCategoryRepository->find($id);
        if(!$category){
            return new JsonResponse('Resource not found.',404 );
        }
        $data = json_decode($request->getContent(),true);
        $form = $this->createForm(ProductCategoryType::class,$category,['csrf_protection'=>false]);
        $form->submit($data);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return new JsonResponse('Edit success');
        }
    }

    /**
     * @Rest\Delete("/{id}")
     * @param int $id
     * @return JsonResponse
     */
    public function deleteCategory(ProductCategoryRepository $productCategoryRepository, int $id)
    {
        $category = $productCategoryRepository->find($id);
        if(!$category){
            return new JsonResponse('Resource not found',404);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return new JsonResponse('Success, product deleted');
    }


}