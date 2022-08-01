<?php
namespace App\Controller;

use App\Entity\Client;
use App\Entity\Company;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/api/company/clients/{limit}/{offset}", name="getClients")
     * @param ManagerRegistry     $doctrine
     * @param SerializerInterface $serializer
     * @param int                 $limit
     * @param int                 $offset
     * @return JsonResponse
     */
    public function showClients(ManagerRegistry $doctrine, SerializerInterface $serializer, int $limit = 8, int $offset = 0): JsonResponse
    {
        /* Get all clients */
        $clients = $doctrine
            ->getRepository(Client::class)
            ->findBy(
                [
                    'company' => $this->getUser()
                ],
                [],
                $limit,
                $offset
            );

        /* Serialisation */
        $context = SerializationContext::create()->setGroups(['getClients']);
        $clients_json = $serializer->serialize($clients, 'json', $context);

        /* Return conditions */
        if (sizeof($clients)>0) {
            return new JsonResponse($clients_json, Response::HTTP_OK, [], true);
        } else {
            return new JsonResponse("", Response::HTTP_NO_CONTENT, [], true);
        }

    }

    /**
     * @Route("/api/clients/add/", name="addClient")
     * @param ManagerRegistry $doctrine
     * @param Request         $request
     * @return JsonResponse
     */
    public function addClient(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!array_key_exists('email', $data)) {
            return new JsonResponse("", Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }
        if (!array_key_exists('firstname', $data)) {
            return new JsonResponse("", Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }
        if (!array_key_exists('lastname', $data)) {
            return new JsonResponse("", Response::HTTP_UNPROCESSABLE_ENTITY, [], true);
        }

        // TODO : Ajouter des vérifications sur l'email etc..

        $client = new Client();
        $client->setEmail($data['email']);
        $client->setFirstname($data['firstname']);
        $client->setLastname($data['lastname']);
        $client->setCompany($this->getUser());

        /* Persist the entity into the database */
        $entityManager = $doctrine->getManager();
        $entityManager->persist($client);
        $entityManager->flush();

        /* Return response */
        return new JsonResponse("", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/api/clients/{client}/delete/", name="deleteClient")
     * @param ManagerRegistry $doctrine
     * @param int             $client
     * @return JsonResponse
     */
    public function deleteClient(ManagerRegistry $doctrine, int $client): JsonResponse
    {
        /* Get client information */
        $client = $doctrine
            ->getRepository(Client::class)
            ->findOneBy(
                [
                    'id' => $client
                ]
            )
        ;
        if (!$client) {
            return new JsonResponse("", Response::HTTP_NO_CONTENT, [], true);
        }

        /* Check permission */
        if ($this->getUser()->getId()!==$client->getCompany()->getId()) {
            return new JsonResponse("", Response::HTTP_FORBIDDEN, [], true);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($client);
        $entityManager->flush();

        /* Return response */
        return new JsonResponse("", Response::HTTP_ACCEPTED, [], true);
    }


    /**
     * @Route("/api/clients/{client}/", name="getClient")
     * @param ManagerRegistry     $doctrine
     * @param SerializerInterface $serializer
     * @param int                 $client
     * @return JsonResponse
     */
    public function showClient(ManagerRegistry $doctrine, SerializerInterface $serializer, int $client): JsonResponse
    {
        /* Get client information */
        $client = $doctrine
            ->getRepository(Client::class)
            ->findOneBy(
                [
                    'id' => $client
                ]
            )
            ;

        /* Check if we have 1 client */
        if (!$client)  {
            return new JsonResponse("", Response::HTTP_NO_CONTENT, [], true);
        }

        /* Check permission */
        if ($this->getUser()->getId()!==$client->getCompany()->getId()) {
            return new JsonResponse("", Response::HTTP_FORBIDDEN, [], true);
        }

        /* Serialisation */
        $context = SerializationContext::create()->setGroups(['getClient']);
        $client_json = $serializer->serialize($client, 'json', $context);

        /* Return content */
        return new JsonResponse($client_json, Response::HTTP_OK, [], true);

    }


}
