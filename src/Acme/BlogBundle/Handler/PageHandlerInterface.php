<?php

namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\ModelInterface;

interface PageHandlerInterface
{
    /**
     * Get a Page given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return ModelInterface
     */
    public function get($id);

    /**
     * Get a list of Pages.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Page, creates a new Page.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return ModelInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Page.
     *
     * @api
     *
     * @param ModelInterface   $page
     * @param array           $parameters
     *
     * @return ModelInterface
     */
    public function put(ModelInterface $page, array $parameters);

    /**
     * Partially update a Page.
     *
     * @api
     *
     * @param ModelInterface   $page
     * @param array           $parameters
     *
     * @return ModelInterface
     */
    public function patch(ModelInterface $page, array $parameters);
    
    /**
     * Delete a Page.
     * 
     * @api
     *
     * @param mixed $id
     *
     * @return void
     */
    public function delete($id);
}