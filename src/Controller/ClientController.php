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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientController extends AbstractController
{
    /**
     * @Route("/api/company/clients/{limit}/{offset}", name="getClients", methods={"GET"})
     * @param ManagerRegistry     $doctrine
     * @param SerializerInterface $serializer
     * @param int                 $limit
     * @param int                 $offset
     * @return JsonResponse
     */
    public function showClients(ManagerRegistry $doctrine, SerializerInterface $serializer, int $limit = 8, int $offset = 0): JsonResponse
    {
        $count = $doctrine
            ->getRepository(Client::class)
            ->count([]);

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

        $data = [
            'clients' => $clients,
            'pages' => []
        ];
        if ($offset-$limit>=0) {
            $data['pages']['previous'] = $this->generateUrl('getClients', [
                'limit' => $limit,
                'offset' => $offset-$limit
            ], UrlGeneratorInterface::ABSOLUTE_URL);
        }
        if (($offset+$limit)+1<=$count) {
            $data['pages']['next'] = $this->generateUrl('getClients', [
                'limit' => $limit,
                'offset' => $offset+$limit
            ], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        /* Serialisation */
        $context = SerializationContext::create()->setGroups(['full_client']);
        $clients_json = $serializer->serialize($data, 'json', $context);

        return new JsonResponse($clients_json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/client/{client}/", name="getClient", methods={"GET"})
     * @param ManagerRegistry     $doctrine
     * @param SerializerInterface $serializer
     * @param                     $client
     * @return JsonResponse
     */
    public function showClient(ManagerRegistry $doctrine, SerializerInterface $serializer, $client): JsonResponse
    {
        if (!is_numeric($client)) {
            return new JsonResponse(json_encode(["error" => "The client ID provided is not correct."]), Response::HTTP_BAD_REQUEST, [], true);
        }
            dd($client);
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
            return new JsonResponse(json_encode(["error" => "Cannot find this client."]), Response::HTTP_NOT_FOUND, [], true);
        }

        /* Check permission */
        if ($this->isGranted('detail_client', $client)) {

            /* Serialisation */
            $context = SerializationContext::create()->setGroups(['full_client']);
            $client_json = $serializer->serialize($client, 'json', $context);

            /* Return content */
            return new JsonResponse($client_json, Response::HTTP_OK, [], true);

        } else {

            return new JsonResponse(json_encode(["error" => "You don't have permission to perform this action."]), Response::HTTP_FORBIDDEN, [], true);

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

        $violations = $validator->validate($client);
        $errors = [
            'errors' => []
        ];
        foreach ($violations as $constraint) {
            $prop = $constraint->getPropertyPath();
            $errors['errors'][$prop][] = $constraint->getMessage();
        }
        if (sizeof($errors['errors'])>0) {
            $errors_json = $serializer->serialize($errors, 'json');
            return new JsonResponse($errors_json, Response::HTTP_BAD_REQUEST, [], true);
        }

        if ($this->isGranted('add_client', $client)) {

            /* Persist the entity into the database */
            $entityManager = $doctrine->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            $context = SerializationContext::create()->setGroups(['full_client']);
            $client_json = $serializer->serialize($client, 'json', $context);

            /* Return response */
            return new JsonResponse($client_json, Response::HTTP_CREATED, [], true);

        } else {
            return new JsonResponse(json_encode(["error" => "You don't have permission to perform this action."]), Response::HTTP_FORBIDDEN, [], true);
        }

    }

    /**
     * @Route("/api/client/{client}/delete/", name="deleteClient", methods={"DELETE"})
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
            return new JsonResponse(json_encode(["error" => "Cannot find this client."]), Response::HTTP_NOT_FOUND, [], true);
        }

        /* Check permission */
        if ($this->isGranted('remove_client', $client)) {

            $entityManager = $doctrine->getManager();
            $entityManager->remove($client);
            $entityManager->flush();

            return new JsonResponse('', Response::HTTP_NO_CONTENT, [], true);

        } else {

            return new JsonResponse(json_encode(["error" => "You don't have permission to perform this action."]), Response::HTTP_FORBIDDEN, [], true);

        }

    }


}
