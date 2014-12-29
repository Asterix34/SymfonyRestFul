<?php

namespace Acme\BlogBundle\DataFixtures\MongoDB;

use Doctrine\Common\Persistence\ObjectManager;
use Acme\BlogBundle\Document\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadPageData extends AbstractFixture implements FixtureInterface
{
    /** 
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
    	for ($i=0;$i<5;$i++) {
	        $product = new Product();
			$product->setName("Ananas au chocolat n°".$i);
			$product->setPrice(12.99+$i);
	
	        $manager->persist($product);
    	}
        
        $manager->flush();
            

    }
}
