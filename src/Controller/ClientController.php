<?php
namespace App\Controller;

use App\Entity\Client;
use App\Entity\Company;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ClientController extends AbstractController
{
    /**
     * @Route("/company/{id}/clients/", name="show_clients")
     * @param ManagerRegistry $doctrine
     * @param int             $id
     * @return Response
     */
    public function showClients(ManagerRegistry $doctrine, int $id): Response
    {
        /* Get company */
        $company = $doctrine
            ->getRepository(Company::class)
            ->findBy(
                [
                    'id' => $id
                ]
            );

        /* Get all clients */
        $clients = $doctrine
            ->getRepository(Client::class)
            ->findBy(
                [
                    'company' => $company
                ]
            );

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $clients = $serializer->normalize($clients, null, [
            AbstractNormalizer::ATTRIBUTES => ['id', 'email', 'firstname', 'lastname', 'company' => ['email', 'name']]
        ]);

        if (sizeof($clients)>0) {
            return $this->json([
                'success' => true,
                'clients' => $clients
            ], 200, [], []);
        } else {
            return $this->json([], 204, [], []);
        }
    }


}