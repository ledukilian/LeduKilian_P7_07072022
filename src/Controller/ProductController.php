<?php
namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products/", name="show_products")
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function showProducts(ManagerRegistry $doctrine): Response
    {
        /* Get all products */
        $products = $doctrine
            ->getRepository(Product::class)
            ->findAll();

        if (sizeof($products)>0) {
            return $this->json([
                'success' => true,
                'products' => $products
            ], 200, [], []);
        } else {
            return $this->json([], 204, [], []);
        }
    }

    /**
     * @Route("/products/{id}/", name="show_product")
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function showProduct(ManagerRegistry $doctrine, int $id): Response
    {
        /* Get one product */
        $product = $doctrine
            ->getRepository(Product::class)
            ->findBy(
                [
                    'id' => $id
                ]
            );
        if (sizeof($product)>0) {
            return $this->json([
                'success' => true,
                'products' => $product
            ], 200, [], []);
        } else {
            return $this->json([], 204, [], []);
        }

    }
}
