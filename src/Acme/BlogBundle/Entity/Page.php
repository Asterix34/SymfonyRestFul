<?php

namespace Acme\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acme\BlogBundle\Model\PageInterface;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Page
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @Serializer\XmlRoot("page")
 * @Hateoas\Relation("self", href = "expr('/api/v1/pages/' ~ object.getId())")
 * @Hateoas\Relation("author", href = "expr('/api/v1/authors/' ~ object.getAuthor().getId())")
 * @Hateoas\Relation("comments", href = "expr('/api/v1/pages/' ~ object.getId() ~ '/comments')")
 */
class Page implements PageInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;
    

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;
    
    /**
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="pages")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Exclude()
     */
    private $author;
    
    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="page")
     * @Serializer\Exclude()
     */
    private $comments;

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
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Page
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Page
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    
        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creationDate = new \DateTime();
    }
    
    /**
     * Set author
     *
     * @param \Acme\BlogBundle\Entity\Author $author
     * @return Page
     */
    public function setAuthor(\Acme\BlogBundle\Entity\Author $author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return \Acme\BlogBundle\Entity\Author 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add comments
     *
     * @param \Acme\BlogBundle\Entity\Comment $comments
     * @return Page
     */
    public function addComment(\Acme\BlogBundle\Entity\Comment $comment)
    {
    	$comment->setPage($this);
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