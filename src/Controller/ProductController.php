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
     * @Route("/api/products/{limit}/{offset}", name="getProducts")
     * @param ManagerRegistry     $doctrine
     * @param SerializerInterface $serializer
     * @param int                 $limit
     * @param int                 $offset
     * @return JsonResponse
     */
    public function showProducts(ManagerRegistry $doctrine, SerializerInterface $serializer, int $limit = 8, int $offset = 0): JsonResponse
    {
        /* Get all products */
        $products = $doctrine
            ->getRepository(Product::class)
            ->findBy(
                [],
                [],
                $limit,
                $offset
            );

        /* Serialisation */
        $context = SerializationContext::create()->setGroups(['getProducts']);
        $products_json = $serializer->serialize($products, 'json', $context);

        /* Return conditions*/
        return new JsonResponse($products_json, Response::HTTP_OK, [], true);
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
        /* Get one product */
        $product = $doctrine
            ->getRepository(Product::class)
            ->findBy(
                [
                    'id' => $product
                ]
            );

        /* Serialisation */
        $context = SerializationContext::create()->setGroups(['getProduct']);
        $product_json = $serializer->serialize($product, 'json', $context);

        /* Return conditions */
        if (!$product)  {
            return new JsonResponse("", Response::HTTP_NOT_FOUND, [], true);
        }
        return new JsonResponse($product_json, Response::HTTP_OK, [], true);
    }
}
