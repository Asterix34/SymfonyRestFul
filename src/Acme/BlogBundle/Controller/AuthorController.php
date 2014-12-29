<?php

namespace Acme\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Acme\BlogBundle\Exception\InvalidFormException;
use Acme\BlogBundle\Form\AuthorType;
use Acme\BlogBundle\Model\AuthorInterface;


class AuthorController extends FOSRestController
{
    /**
     * List all authors.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   
     * )
     *
     * @Rest\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing authors.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="5", description="How many authors to return.")
     *
     * @Rest\View(
     *  templateVar="authors"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getAuthorsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('acme_blog.author.handler')->all($limit, $offset);
    }

    /**
     * Get single Author.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Author for a given id",
     *   output = "Acme\BlogBundle\Entity\Author",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the author is not found"
     *   }
     * )
     *
     * @Rest\View(templateVar="author")
     *
     * @param int     $id      the author id
     *
     * @return array
     *
     * @throws NotFoundHttpException when author not exist
     */
    public function getAuthorAction($id)
    {
        $author = $this->getOr404($id);

        return $author;
    }

    /**
     * Presents the form to use to create a new author.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Rest\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newAuthorAction()
    {
        return $this->createForm(new AuthorType());
    }

    /**
     * Create a Author from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new author from the submitted data.",
     *   input = "Acme\BlogBundle\Form\AuthorType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Rest\View(
     *  template = "AcmeBlogBundle:Author:newAuthor.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postAuthorAction(Request $request)
    {
        try {
            $newAuthor = $this->container->get('acme_blog.author.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newAuthor->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_author', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing author from the submitted data or create a new author at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Acme\DemoBundle\Form\AuthorType",
     *   statusCodes = {
     *     201 = "Returned when the Author is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Rest\View(
     *  template = "AcmeBlogBundle:Author:editAuthor.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the author id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when author not exist
     */
    public function putAuthorAction(Request $request, $id)
    {
        try {
            if (!($author = $this->container->get('acme_blog.author.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $author = $this->container->get('acme_blog.author.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $author = $this->container->get('acme_blog.author.handler')->put(
                    $author,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $author->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_author', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing author from the submitted data or create a new author at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Acme\DemoBundle\Form\AuthorType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Rest\View(
     *  template = "AcmeBlogBundle:Author:editAuthor.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the author id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when author not exist
     */
    public function patchAuthorAction(Request $request, $id)
    {
        try {
            $author = $this->container->get('acme_blog.author.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $author->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_author', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }
    
    /**
     * Delete a Author.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Delete a Author for a given id",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the author is not found"
     *   }
     * )
     *
     * @param mixed     $id      the author id
     *
     * @return View
     *
     * @throws NotFoundHttpException when author not exist
     */
    public function deleteAuthorAction($id)
    {
    	$author = $this->getOr404($id);
    
    	$this->container->get('acme_blog.author.handler')->delete($id);
    	
    	return $this->view(null, Codes::HTTP_NO_CONTENT);
    }

    /**
     * Fetch a Author or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return AuthorInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($author = $this->container->get('acme_blog.author.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $author;
    }
}
