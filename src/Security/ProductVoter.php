<?php

namespace App\Security;

use App\Entity\Company;
use App\Entity\Product;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ProductVoter extends Voter
{
    const LIST = 'list_products';
    const DETAILS = 'detail_product';
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
        if (!in_array($attribute, [self::LIST, self::DETAILS])) {
            return false;
        }

        /* If attribute is not a Product, return false */
        if (!$subject instanceof Product) {
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

        /** @var Product $product */
        $product = $subject;

        /* Switch with 2 actions on Product element */
        switch ($attribute) {
            case self::LIST:
                return true;
            case self::DETAILS:
                return $this->canShowDetails($product, $company);
        }
        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param Product $product
     * @param Company  $company
     * @return bool
     */
    private function canShowDetails(Product $product, Company $company): bool
    {
        return true;
    }

}