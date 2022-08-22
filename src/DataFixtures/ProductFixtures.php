<?php
namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product01 = new Product();
        $product01->setBrand('Samsung');
        $product01->setName('S21 Ultra');
        $product01->setDescription('Le Samsung Galaxy S21 ultra est le modèle haut de gamme absolu de la dernière série de Samsung Galaxy S21.');
        $product01->setPrice("719.99");

        $product02 = new Product();
        $product02->setBrand('Samsung');
        $product02->setName('Galaxy Z Fold3');
        $product02->setDescription('L’un des smartphones les plus ambitieux de ces dernières années');
        $product02->setPrice("1299.99");

        $product03 = new Product();
        $product03->setBrand('Apple');
        $product03->setName('iPhone 13 Pro');
        $product03->setDescription('Votre nouveau superpouvoir. Puissance irrésistible. À prix irrésistible.');
        $product03->setPrice("1159");

        $product04 = new Product();
        $product04->setBrand('Apple');
        $product04->setName('iPhone SE');
        $product04->setDescription('Avec la puce A15 Bionic, votre expérience est améliorée à tous les niveaux. Les apps se téléchargent en un éclair et fonctionnent de manière incroyablement fluide.');
        $product04->setPrice("529");

        $product05 = new Product();
        $product05->setBrand('Xiaomi');
        $product05->setName('12X');
        $product05->setDescription('Maîtrisez toutes les scènes, le flagship, version compacte. 69,9 mm de large, ça change tout.');
        $product05->setPrice("799.90");

        $product06 = new Product();
        $product06->setBrand('Redmi');
        $product06->setName('Note 11 Pro+ 5G');
        $product06->setDescription('Changez votre quotidien en chargeant votre appareil à 100 % en seulement 15 minutes en mode Boost* grâce à l\'HyperCharge 120W ultra-rapide du Redmi Note 11 Pro+ 5G.');
        $product06->setPrice("419.90");

        $product07 = new Product();
        $product07->setBrand('OnePlus');
        $product07->setName('OnePlus 10T 5G');
        $product07->setDescription('e OnePlus 10T 5G est le fleuron de la vitesse qui offre des performances ultimes. ');
        $product07->setPrice("729.00");

        $product08 = new Product();
        $product08->setBrand('OnePlus');
        $product08->setName('Quasiment tout ce que vous attendiez.');
        $product08->setDescription('');
        $product08->setPrice("429.00");

        $product09 = new Product();
        $product09->setBrand('OnePlus');
        $product09->setName('OnePlus 9 Pro');
        $product09->setDescription('Your best shot.');
        $product09->setPrice("919.00");

        $manager->persist($product01);
        $manager->persist($product02);
        $manager->persist($product03);
        $manager->persist($product04);
        $manager->persist($product05);
        $manager->persist($product06);
        $manager->persist($product07);
        $manager->persist($product08);
        $manager->persist($product09);
        $manager->flush();
    }
}
