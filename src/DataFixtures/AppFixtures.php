<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Backlog;
use App\Entity\BacklogItem;
use App\Enum\BacklogItemStatus;
use App\Enum\BacklogItemType;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $backlog = new Backlog();
        $backlog->setName('Sample Backlog List');
        $backlog->setDescription('This is a sample backlog list.');

        $item = new BacklogItem();
        $item->setBacklog($backlog);
        $item->setType(BacklogItemType::ALBUM);
        $item->setStatus(BacklogItemStatus::PLANNED);
        $item->setNote('This is a sample backlog item.');

        // tell Doctrine you want to (eventually) save the Backlog (no queries yet)
        $manager->persist($backlog);
        $manager->persist($item);

        $item = new BacklogItem();
        $item->setBacklog($backlog);
        $item->setType(BacklogItemType::ARTIST);
        $item->setStatus(BacklogItemStatus::PLANNED);
        $item->setNote('This is a sample backlog artist.');

        $manager->persist($item);

        // actually executes the queries (i.e. the INSERT query)
        $manager->flush();
    }
}
