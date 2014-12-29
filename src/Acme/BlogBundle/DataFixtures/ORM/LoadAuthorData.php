<?php

namespace Acme\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\BlogBundle\Entity\Author;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadAuthorData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /** 
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $author = new Author();
        $author->setName('michel');
        $author->setLogin('michel1234');
        $author->setPassword('test');

        $manager->persist($author);
        $this->addReference('michel', $author);
        
        $author = new Author();
        $author->setName('paul');
        $author->setLogin('paul1234');
        $author->setPassword('test');
        
        $manager->persist($author);
        $this->addReference('paul', $author);
        
        $manager->flush();
        

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
