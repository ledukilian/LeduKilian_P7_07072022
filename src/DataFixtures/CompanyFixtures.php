<?php
namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CompanyFixtures extends Fixture
{
    public const COMPANY1_REFERENCE = 'company-1';
    public const COMPANY2_REFERENCE = 'company-2';
    public const COMPANY3_REFERENCE = 'company-3';
    public const DEFAULT_PASSWORD = 'bilemo';
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        /*  Creating first Company */
        $company01 = new Company();
        $company01->setName('Bilemo SAS');
        $company01->setEmail('admin@bilemo.fr');
        $password = $this->hasher->hashPassword($company01, self::DEFAULT_PASSWORD);
        $company01->setPassword($password);
        $company01->setRoles(["ROLE_ADMIN"]);

        /*  Creating second Company */
        $company02 = new Company();
        $company02->setName('A-Company');
        $company02->setEmail('admin@acompany.com');
        $password = $this->hasher->hashPassword($company02, self::DEFAULT_PASSWORD);
        $company02->setPassword($password);
        $company02->setRoles([""]);

        /*  Creating third Company */
        $company03 = new Company();
        $company03->setName('B Corp');
        $company03->setEmail('admin@b-corp.fr');
        $password = $this->hasher->hashPassword($company03, self::DEFAULT_PASSWORD);
        $company03->setPassword($password);
        $company03->setRoles([""]);


        $manager->persist($company01);
        $manager->persist($company02);
        $manager->persist($company03);
        $manager->flush();

        $this->addReference(self::COMPANY1_REFERENCE, $company01);
        $this->addReference(self::COMPANY2_REFERENCE, $company02);
        $this->addReference(self::COMPANY3_REFERENCE, $company03);

    }
}
