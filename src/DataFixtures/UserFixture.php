<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $person = new Person();

        $person
            ->setFirstName('admin')
            ->setLastName(' ACESSO')
            ->setNumberId('123456789')
            ->setEmail('admin@hotmail.com')
            ->setHeigth(null)
            ->setWeight(null)
            ->setCreated(new \DateTime('@'.strtotime('now')));

         $manager->persist($person);
         $manager->flush();


        $user = new User();
        $user->setUsername('admin') #senha:123456
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$N2VGeGVoNEQuaDYwaFJ4Wg$NBF9+JWNlmyEjeAhpDlCoBem0GQLUbtZOiXuLb3p1oU')
            ->setRoles(['ROLE_ADMIN'])
            ->setPerson($person);
        $manager->persist($user);

        $manager->flush();
    }
}
