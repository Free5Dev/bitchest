<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Users;

class UsersFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder= $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");
        for($i=1; $i<=10; $i++){
            $users= new Users();
            $password=$this->encoder->encodePassword($users, 'password');
            $users->setUsername($faker->userName)
                  ->setPassword($password)
                  ->setEmail($faker->email)
                  ->setFirstname($faker->firstname())
                  ->setLastname($faker->lastname)
                  ->setCity($faker->city)
                  ->setAddress($faker->address)
                  ->setCp("94000")
                  ->setPhone("076497270")
                  ->setBalance(0.0);
            $manager->persist($users);
        }

        $manager->flush();
    }
}
