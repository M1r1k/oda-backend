<?php

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TicketCategoryController extends FOSRestController
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $ticketCategoryRepository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    public function manualConstruct()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->ticketCategoryRepository = $this->entityManager->getRepository('ODADneprMockServiceBundle:TicketCategory');
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Returns list of ticket categories",
     *   output="ODADnepr\MockServiceBundle\Entity\TicketCategory",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     *
     * @Route("/rest/v1/ticket-categories")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $this->manualConstruct();

        $tickets = $this->ticketCategoryRepository->findAll();
        return $tickets;
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Returns Ticket Category with requested ID",
     *   requirements={
     *     {
     *       "name"="category_id",
     *       "dataType"="integer",
     *       "required"=true,
     *       "description"="Manager ID"
     *     }
     *   },
     *   output="ODADnepr\MockServiceBundle\Entity\TicketCategory",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     *
     * @Route("/rest/v1/ticket-category/{category_id}")
     * @Method({"GET"})
     */
    public function getAction($category_id)
    {
        $this->manualConstruct();

        return $this->ticketCategoryRepository->find($category_id);
    }
}
