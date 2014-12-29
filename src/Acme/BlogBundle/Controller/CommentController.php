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
use Acme\BlogBundle\Form\PageType;
use Acme\BlogBundle\Model\PageInterface;


class CommentController extends FOSRestController
{
    /**
     * List all comments from a page.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   
     * )
     *
     * @Rest\RequestParam(name="id", requirements="\d+",description="Id of the page")
     * @Rest\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing pages.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="5", description="How many pages to return.")
     * @Rest\Get("/v1/pages/{id}/comments")
     *
     * @Rest\View(
     *  templateVar="comments"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     * @param int					$id			  the page id
     *
     * @return array
     */
    public function getCommentsAction(Request $request, ParamFetcherInterface $paramFetcher, $id)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('acme_blog.comment.handler')->all($limit, $offset, array('page'=>$id));
    }

    /**
     * Gets a comment for a given id
     *
     * @ApiDoc(
     *   resource = true,
     *   output = "Acme\BlogBundle\Entity\Comment",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the page is not found"
     *   }
     * )
     *
     * @Rest\View(templateVar="comment")
     *
     * @param int     $id      the page id
     * @param int     $cid      the comment id
     *
     * @return array
     *
     * @throws NotFoundHttpException when page not exist
     */
    public function getCommentAction($id, $cid)
    {
        $c = $this->getOr404($cid);

        return $c;
    }

    /**
     * Fetch a Page or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return PageInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($page = $this->container->get('acme_blog.comment.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $page;
    }
}
