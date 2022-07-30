<?php
namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/products/", name="getProducts")
     * @param ManagerRegistry     $doctrine
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function showProducts(ManagerRegistry $doctrine, SerializerInterface $serializer): JsonResponse
    {
        /* Get all products */
        $products = $doctrine
            ->getRepository(Product::class)
            ->findAll();

        $context = SerializationContext::create()->setGroups(['getProducts']);
        $products = $serializer->serialize($products, 'json', $context);

        if (!empty($products)) {
            return new JsonResponse($products, Response::HTTP_OK, [], true);
        } else {
            return new JsonResponse("", Response::HTTP_NO_CONTENT, [], true);
        }
    }

    /**
     * @Route("/api/products/{product}/", name="getProduct")
     * @param ManagerRegistry     $doctrine
     * @param SerializerInterface $serializer
     * @param int                 $product
     * @return JsonResponse
     */
    public function showProduct(ManagerRegistry $doctrine, SerializerInterface $serializer, int $product): JsonResponse
    {
        $product = $doctrine
            ->getRepository(Product::class)
            ->findBy(
                [
                    'id' => $product
                ]
            );

        /* Get one product */
        $context = SerializationContext::create()->setGroups(['getProduct']);
        $product_json = $serializer->serialize($product, 'json', $context);

        if (sizeof($product)>0) {
            return new JsonResponse($product_json, Response::HTTP_OK, [], true);
        } else {
            return new JsonResponse("", Response::HTTP_NO_CONTENT, [], true);
        }
    }
}
