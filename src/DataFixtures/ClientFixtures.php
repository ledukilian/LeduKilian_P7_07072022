<?php
namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /*  Creating clients for first company */
        $client01 = new Client();
        $client01->setEmail('anna.rtichaud@bilemo.fr');
        $client01->setCompany($this->getReference(CompanyFixtures::COMPANY1_REFERENCE));
        $client01->setFirstname('Anna');
        $client01->setLastname('Rtichaud');

        $client02 = new Client();
        $client02->setEmail('alain.proviste@bilemo.fr');
        $client02->setCompany($this->getReference(CompanyFixtures::COMPANY1_REFERENCE));
        $client02->setFirstname('Alain');
        $client02->setLastname('Proviste');

        $client03 = new Client();
        $client03->setEmail('elie.coptere@bilemo.fr');
        $client03->setCompany($this->getReference(CompanyFixtures::COMPANY1_REFERENCE));
        $client03->setFirstname('Élie');
        $client03->setLastname('Coptère');


        /*  Creating clients for first company */
        $client04 = new Client();
        $client04->setEmail('anna.spirateur@acompany.com');
        $client04->setCompany($this->getReference(CompanyFixtures::COMPANY2_REFERENCE));
        $client04->setFirstname('Anna');
        $client04->setLastname('Spirateur');

        $client05 = new Client();
        $client05->setEmail('eva.zion-fiscale@acompany.com');
        $client05->setCompany($this->getReference(CompanyFixtures::COMPANY2_REFERENCE));
        $client05->setFirstname('Éva');
        $client05->setLastname('Zion-Fiscale');

        $client06 = new Client();
        $client06->setEmail('julie.unlivre@acompany.com');
        $client06->setCompany($this->getReference(CompanyFixtures::COMPANY2_REFERENCE));
        $client06->setFirstname('Julie');
        $client06->setLastname('Unlivre');


        /*  Creating clients for first company */
        $client07 = new Client();
        $client07->setEmail('oussama.lairbon@b-corp.fr');
        $client07->setCompany($this->getReference(CompanyFixtures::COMPANY3_REFERENCE));
        $client07->setFirstname('Oussama');
        $client07->setLastname('Lairbon');

        $client08 = new Client();
        $client08->setEmail('marc.assain@b-corp.fr');
        $client08->setCompany($this->getReference(CompanyFixtures::COMPANY3_REFERENCE));
        $client08->setFirstname('Marc');
        $client08->setLastname('Assain');

        $client09 = new Client();
        $client09->setEmail('vincent.douiche@b-corp.fr');
        $client09->setCompany($this->getReference(CompanyFixtures::COMPANY3_REFERENCE));
        $client09->setFirstname('Vincent');
        $client09->setLastname('Douiche');

        $manager->persist($client01);
        $manager->persist($client02);
        $manager->persist($client03);
        $manager->persist($client04);
        $manager->persist($client05);
        $manager->persist($client06);
        $manager->persist($client07);
        $manager->persist($client08);
        $manager->persist($client09);
        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            CompanyFixtures::class,
        ];
    }
}
