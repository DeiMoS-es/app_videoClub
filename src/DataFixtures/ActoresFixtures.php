<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActoresFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $actor1 = new Actor();
        $actor1->setNombre('Christian bale');
        $actor1->setFotoActor('no.jpg');
        $manager->persist($actor1);

        $actor2 = new Actor();
        $actor2->setNombre('Heath Ledger');
        $actor2->setFotoActor('no.jpg');
        $manager->persist($actor2);

        $actor3 = new Actor();
        $actor3->setNombre('Robert Downey Jr');
        $actor3->setFotoActor('no.jpg');
        $manager->persist($actor3);

        $actor4 = new Actor();
        $actor4->setNombre('Chris Evans');
        $actor4->setFotoActor('no.jpg');
        $manager->persist($actor4);

        $manager->flush();

        $this->addReference('actor_1', $actor1);
        $this->addReference('actor_2', $actor2);
        $this->addReference('actor_3', $actor3);
        $this->addReference('actor_4', $actor4);
    }
}
