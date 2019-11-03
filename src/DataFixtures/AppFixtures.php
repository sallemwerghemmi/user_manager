<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
	for($i =1 ; $i < 10; $i++){
	                $user = new User();
	                $user->setFirstName('first name N°$i')
	                        ->setLastName('last name N°$i')
	                        ->setCreatedAt(new \DateTime());
	                $manager->persist($user);
	}


	        $manager->flush();
	    }
}
