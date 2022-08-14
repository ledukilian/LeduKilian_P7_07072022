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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

        // TODO : Gestion de la pagination

        /* Return conditions */
        if (sizeof($clients)>0) {
            return new JsonResponse($clients_json, Response::HTTP_OK, [], true);
        }

    }

    /**
     * @Route("/api/clients/add/", name="addClient", methods={"POST"})
     * @param ValidatorInterface  $validator
     * @param ManagerRegistry     $doctrine
     * @param SerializerInterface $serializer
     * @param Request             $request
     * @return JsonResponse
     */
    public function addClient(ValidatorInterface $validator, ManagerRegistry $doctrine, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $client = $serializer->deserialize($request->getContent(), Client::class, "json");
        $client->setCompany($this->getUser());

        $errors = $validator->validate($client);

        if (count($errors) > 0) {
            $errors = (string) $errors;
            $errors_json = $serializer->serialize($errors, 'json');

            return new JsonResponse($errors_json, Response::HTTP_BAD_REQUEST, [], true);
        }

        if ($this->isGranted('add_client', $client)) {

            /* Persist the entity into the database */
            $entityManager = $doctrine->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            $context = SerializationContext::create()->setGroups(['getClient']);
            $client_json = $serializer->serialize($client, 'json', $context);

            /* Return response */
            return new JsonResponse($client_json, Response::HTTP_CREATED, [], true);

        } else {
            return new JsonResponse("", Response::HTTP_FORBIDDEN, [], true);
        }

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
            return new JsonResponse("", Response::HTTP_NOT_FOUND, [], true);
        }

        /* Check permission */
        if ($this->isGranted('remove_client', $client)) {

            $entityManager = $doctrine->getManager();
            $entityManager->remove($client);
            $entityManager->flush();

            return new JsonResponse("", Response::HTTP_NO_CONTENT, [], true);

        } else {

            return new JsonResponse("", Response::HTTP_FORBIDDEN, [], true);

        }

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
            return new JsonResponse("", Response::HTTP_NOT_FOUND, [], true);
        }

        /* Check permission */
        if ($this->isGranted('detail_client', $client)) {

            /* Serialisation */
            $context = SerializationContext::create()->setGroups(['getClient']);
            $client_json = $serializer->serialize($client, 'json', $context);

            /* Return content */
            return new JsonResponse($client_json, Response::HTTP_OK, [], true);

        } else {

            return new JsonResponse("", Response::HTTP_FORBIDDEN, [], true);

        }



    }


}
