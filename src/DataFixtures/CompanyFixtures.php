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
        $company01->setEmail('admin@bilemo.fr');
        $company01->setPassword('$2y$13$pnXVA1WXxikRzYkc41FYPuyhVA4Dcv57uOjP9bAgQuRr8aXVHY17q');
        $company01->setRoles(["ROLE_ADMIN"]);
        $company01->setName('Bilemo SAS');

        /*  Creating second Company */
        $company02 = new Company();
        $company02->setEmail('anna.rtichaud@acompany.fr');
        $company02->setPassword('$2y$13$pnXVA1WXxikRzYkc41FYPuyhVA4Dcv57uOjP9bAgQuRr8aXVHY17q');
        $company02->setRoles([""]);
        $company02->setName('A-Company');

        /*  Creating third Company */
        $company03 = new Company();
        $company03->setEmail('yves.atroloin@snowtricks.fr');
        $company03->setPassword('$2y$13$pnXVA1WXxikRzYkc41FYPuyhVA4Dcv57uOjP9bAgQuRr8aXVHY17q');
        $company03->setRoles([""]);
        $company03->setName('Snowtricks Blog');


        $manager->persist($company01);
        $manager->persist($company02);
        $manager->persist($company03);
        $manager->flush();

        $this->addReference(self::COMPANY1_REFERENCE, $company01);
        $this->addReference(self::COMPANY2_REFERENCE, $company02);
        $this->addReference(self::COMPANY3_REFERENCE, $company03);

    }
}
