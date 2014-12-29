<?php

namespace Acme\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ORD;

use Acme\BlogBundle\Model\AuthorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Author
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\BlogBundle\Entity\AuthorRepository")
 * @ORD\Document
 * @Serializer\XmlRoot("author")
 * 
 * @Hateoas\Relation("self", href = "expr('/api/v1/authors/' ~ object.getId())")
 * @Hateoas\Relation(
 *     name = "pages",
 *     embedded = @Hateoas\Embedded(
 *         "expr(object.getPages())",
 *         xmlElementName = "pages"
 *     )
 * )
 * 
 */
class Author implements AuthorInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\XmlAttribute()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;
    
    /**
     * 
     * @var Collection
     * 
     * @ORM\OneToMany(targetEntity="Page", mappedBy="author", cascade="remove")
     * @Serializer\Exclude();
     */
    private $pages;

    /**
     *
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="author", cascade="remove")
     * @Serializer\Exclude();
     */
    private $comments;
    

    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set id
     *
     * @return Author
     */
    /*public function setId($id)
    {
    	$this->id = $id;
    	return $this;
    }*/
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	

    /**
     * Set name
     *
     * @param string $name
     * @return Author
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return Author
     */
    public function setLogin($login)
    {
        $this->login = $login;
    
        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Author
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Add pages
     *
     * @param \Acme\BlogBundle\Entity\Page $pages
     * @return Author
     */
    public function addPage(\Acme\BlogBundle\Entity\Page $page)
    {
        $page->setAuthor($this);
    	$this->pages[] = $page;
    
        return $this;
    }

    /**
     * Remove pages
     *
     * @param \Acme\BlogBundle\Entity\Page $pages
     */
    public function removePage(\Acme\BlogBundle\Entity\Page $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Add comments
     *
     * @param \Acme\BlogBundle\Entity\Comment $comments
     * @return Author
     */
    public function addComment(\Acme\BlogBundle\Entity\Comment $comment)
    {
    	$comment->setAuthor($this);
        $this->comments[] = $comment;
    
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Acme\BlogBundle\Entity\Comment $comments
     */
    public function removeComment(\Acme\BlogBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }
}