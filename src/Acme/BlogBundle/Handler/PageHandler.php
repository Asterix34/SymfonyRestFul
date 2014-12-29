<?php

namespace Acme\BlogBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Acme\BlogBundle\Model\ModelInterface;
use Acme\BlogBundle\Form\PageType;
use Acme\BlogBundle\Exception\InvalidFormException;
use Acme\BlogBundle\Form\AuthorType;
use Acme\BlogBundle\Form\CommentType;

class PageHandler implements PageHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a Page.
     *
     * @param mixed $id
     *
     * @return ModelInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Pages.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0, $params=array())
    {
        return $this->repository->findBy($params, null, $limit, $offset);
    }

    /**
     * Create a new Page.
     *
     * @param array $parameters
     *
     * @return ModelInterface
     */
    public function post(array $parameters)
    {
        $page = $this->createPage();

        return $this->processForm($page, $parameters, 'POST');
    }

    /**
     * Edit a Page.
     *
     * @param ModelInterface $page
     * @param array         $parameters
     *
     * @return ModelInterface
     */
    public function put(ModelInterface $model, array $parameters)
    {
        return $this->processForm($model, $parameters, 'PUT');
    }

    /**
     * Partially update a Page.
     *
     * @param ModelInterface $page
     * @param array         $parameters
     *
     * @return ModelInterface
     */
    public function patch(ModelInterface $model, array $parameters)
    {
        return $this->processForm($model, $parameters, 'PATCH');
    }
    
    /**
     * Delete a Page.
     *
     * @param mixed $id
     *
     * @return void
     */
    public function delete($id)
    {
    	$model = $this->get($id);
    	$this->om->remove($model);
    	$this->om->flush ();
    	
    	return null;
    }

    /**
     * Processes the form.
     *
     * @param ModelInterface $model
     * @param array         $parameters
     * @param String        $method
     *
     * @return ModelInterface
     *
     * @throws \Acme\BlogBundle\Exception\InvalidFormException
     */
    private function processForm(ModelInterface $model, array $parameters, $method = "PUT")
    {
    	switch (get_class($model)) {
    		case 'Acme\BlogBundle\Entity\Author':
    			$form = new AuthorType();
    			break;
    		case 'Acme\BlogBundle\Entity\Comment':
    			$form = new CommentType();
    			break;
    		default:
    		case 'Acme\BlogBundle\Entity\Page':
    				$form = new PageType();
    				break;
    		
    	}
        $form = $this->formFactory->create($form, $model, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $model = $form->getData();
            $this->om->persist($model);
            $this->om->flush($model);

            return $model;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createPage()
    {
        return new $this->entityClass();
    }

}