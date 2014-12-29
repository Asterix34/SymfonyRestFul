<?php

namespace Acme\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Acme\BlogBundle\Entity\Page;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadPageData extends AbstractFixture implements OrderedFixtureInterface
{
    /** 
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
    	for ($i=0;$i<5;$i++) {
	        $page = new Page();
			$page->setTitle("Page de test ".$i);
			$page->setBody("Contenu inutile numéro ".$i);
			$page->setAuthor($this->getReference("michel"));
			$this->addReference("michel-article".($i+1), $page);
	
	        $manager->persist($page);
    	}
        
       	for ($i=0;$i<5;$i++) {
	        $page = new Page();
			$page->setTitle("Page 2 de test ".$i);
			$page->setBody("Contenu inutile numéro ".$i." featuring martin ou paul");
			$page->setAuthor($this->getReference("paul"));
			$this->addReference("paul-article".($i+1), $page);
	
	        $manager->persist($page);
    	}
        
        $manager->flush();
            

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
