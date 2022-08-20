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
use App\Services\PaginationService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/products/{limit}/{offset}", name="getProducts", methods={"GET"})
     * @param ManagerRegistry     $doctrine
     * @param SerializerInterface $serializer
     * @param int                 $limit
     * @param int                 $offset
     * @return JsonResponse
     */
    public function showProducts(ManagerRegistry $doctrine, SerializerInterface $serializer, int $limit = 8, int $offset = 0): JsonResponse
    {
        $count = $doctrine
            ->getRepository(Product::class)
            ->count([]);

        $products = $doctrine
            ->getRepository(Product::class)
            ->findBy(
                [],
                [],
                $limit,
                $offset,
            );

        $data = [
            'products' => $products,
            'pages' => []
        ];
        if ($offset-$limit>=0) {
            $data['pages']['previous'] = $this->generateUrl('getProducts', [
                'limit' => $limit,
                'offset' => $offset-$limit
            ], UrlGeneratorInterface::ABSOLUTE_URL);
        }
        if (($offset+$limit)+1<=$count) {
            $data['pages']['next'] = $this->generateUrl('getProducts', [
                'limit' => $limit,
                'offset' => $offset+$limit
            ], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        /* Serialisation */
        $context = SerializationContext::create()->setGroups(['full_product']);
        $products_json = $serializer->serialize($data, 'json', $context);

        /* Return conditions*/
        return new JsonResponse($products_json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/product/{product}/", name="getProduct", methods={"GET"})
     * @param ManagerRegistry     $doctrine
     * @param SerializerInterface $serializer
     * @param int             $product
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
        $context = SerializationContext::create()->setGroups(['full_product']);
        $product_json = $serializer->serialize($product, 'json', $context);

        /* Return conditions */
        if (!$product)  {
            return new JsonResponse(json_encode(["error" => "Cannot find this product."]), Response::HTTP_NOT_FOUND, [], true);
        }
        return new JsonResponse($product_json, Response::HTTP_OK, [], true);
    }
}
