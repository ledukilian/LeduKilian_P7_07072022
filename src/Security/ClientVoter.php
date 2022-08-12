<?php

namespace App\Security;

use App\Entity\Company;
use App\Entity\Client;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ClientVoter extends Voter
{
    const LIST = 'list_clients';
    const DETAILS = 'detail_client';
    const ADD = 'add_client';
    const REMOVE = 'remove_client';
    private Security $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        /* If attribute is not supported, return false */
        if (!in_array($attribute, [self::LIST, self::DETAILS, self::ADD, self::REMOVE])) {
            return false;
        }

        /* If attribute is not a Client, return false */
        if (!$subject instanceof Client) {
            return false;
        }

        /* Else, return false, attribute and subject are not supported */
        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $company = $token->getUser();

        if (!$company instanceof Company) {
            return false;
        }

        /** @var Client $client */
        $client = $subject;

        /* Switch with 4 actions on Client element */
        switch ($attribute) {
            case self::LIST:
                return true;
            case self::DETAILS:
                return $this->canShowDetails($client, $company);
            case self::ADD:
                return $this->canAdd($client, $company);
            case self::REMOVE:
                return $this->canRemove($client, $company);
        }
        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param Client  $client
     * @param Company $company
     * @return bool
     */
    private function canShowDetails(Client $client, Company $company): bool
    {
        if ($company->getId()!==$client->getCompany()->getId()) {
            return false;
        }
        return true;
    }

    /**
     * @param Client  $client
     * @param Company $company
     * @return bool
     */
    private function canAdd(Client $client, Company $company): bool
    {
        if ($company->getId()!==$client->getCompany()->getId()) {
            return false;
        }
        return true;
    }

    /**
     * @param Client  $client
     * @param Company $company
     * @return bool
     */
    private function canRemove(Client $client, Company $company): bool
    {
        if ($company->getId()!==$client->getCompany()->getId()) {
            return false;
        }
        return true;
    }

}