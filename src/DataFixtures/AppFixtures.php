<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\StoreProduct;
use App\Entity\Store;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\Address;
use App\Entity\UserType;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $client = new UserType();
        $client->setType('Client');
        $manager->persist($client);
        
        $driver = new UserType();
        $driver->setType('Shopper');
        $manager->persist($driver);

        $spain = new Country();
        $spain->setName('Spain');
        $spain->setSlug('SP');
        $manager->persist($spain);

        $madrid = new City();
        $madrid->setName('Madrid');
        $madrid->setCountry($spain);
        $manager->persist($madrid);

        $carrefourAddress = new Address();
        $carrefourAddress->setVia('Calle Cartagena');
        $carrefourAddress->setNumber('74');
        $carrefourAddress->setCity($madrid);
        $manager->persist($carrefourAddress);

        $carrefour = new Store();
        $carrefour->setName('Carrefour Market');
        $carrefour->setAddress($carrefourAddress);
        $manager->persist($carrefour);

        $diaAddress = new Address();
        $diaAddress->setVia('Calle Toreros');
        $diaAddress->setNumber('74');
        $diaAddress->setCity($madrid);
        $manager->persist($diaAddress);

        $dia = new Store();
        $dia->setName('DIA');
        $dia->setAddress($diaAddress);
        $manager->persist($dia);

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName('product '.$i);
            $product->setDescription('description '.$i);
            $manager->persist($product);

            $orderProduct = new StoreProduct();
            $orderProduct->setProduct($product);
            $orderProduct->setStore($carrefour);
            $orderProduct->setPrice(mt_rand(10, 100));
            $manager->persist($orderProduct);

            $orderProduct = new StoreProduct();
            $orderProduct->setProduct($product);
            $orderProduct->setStore($dia);
            $orderProduct->setPrice(mt_rand(10, 100));
            $manager->persist($orderProduct);

            $user = new User();
            $user->setName('name '.$i);
            $user->setLastname('lastname '.$i);
            $user->setEmail('email'.$i.'client@gmail.com');
            $user->setType($client);
            $manager->persist($user);

            $user = new User();
            $user->setName('name '.$i);
            $user->setLastname('lastname '.$i);
            $user->setEmail('email'.$i.'@gmail.com');
            $user->setType($driver);
            $manager->persist($user);

        }

        $manager->flush();
        
    }
}
