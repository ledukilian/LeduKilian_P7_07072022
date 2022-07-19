<?php
namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public const COMPANY1_REFERENCE = 'company-1';
    public const COMPANY2_REFERENCE = 'company-2';
    public const COMPANY3_REFERENCE = 'company-3';

    public function load(ObjectManager $manager): void
    {
        /*  Creating first Company */
        $company01 = new Company();
        $company01->setUsername('Administrateur');


        $manager->persist($company01);
        $manager->flush();

        $this->addReference(self::COMPANY1_REFERENCE, $company01);

    }
}
