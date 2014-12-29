<?php

namespace Acme\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\BlogBundle\Entity\Comment;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadCommentData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /** 
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
    	for ($i=0;$i<5;$i++) {
	        $comment = new Comment();
			$comment->setTitle("Commentaire ".$i);
			$comment->setBody("Contenu inutile numéro ".$i);
			$comment->setAuthor($this->getReference("paul"));
			$comment->setPage($this->getReference("michel-article".($i+1)));
	
	        $manager->persist($comment);
    	}
        
       	for ($i=0;$i<5;$i++) {
	        $comment = new Comment();
			$comment->setTitle("Commentaire ".$i);
			$comment->setBody("Contenu inutile numéro ".$i);
			$comment->setAuthor($this->getReference("michel"));
			$comment->setPage($this->getReference("paul-article".($i+1)));
	
	        $manager->persist($comment);
    	}
        
        $manager->flush();
            

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
