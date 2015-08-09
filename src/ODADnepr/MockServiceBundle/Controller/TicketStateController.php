<?php

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TicketStateController extends FOSRestController
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $ticketStateRepository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    public function manualConstruct()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->ticketStateRepository = $this->entityManager->getRepository('ODADneprMockServiceBundle:TicketState');
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Returns list of ticket states",
     *   output="ODADnepr\MockServiceBundle\Entity\TicketState",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     *
     * @Route("/rest/v1/ticket-states")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $this->manualConstruct();

        $tickets = $this->ticketStateRepository->findAll();
        return $tickets;
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Returns Ticket State with requested ID",
     *   requirements={
     *     {
     *       "name"="state_id",
     *       "dataType"="integer",
     *       "required"=true,
     *       "description"="State ID"
     *     }
     *   },
     *   output="ODADnepr\MockServiceBundle\Entity\TicketState",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     *
     * @Route("/rest/v1/ticket-category/{state_id}")
     * @Method({"GET"})
     */
    public function getAction($state_id)
    {
        $this->manualConstruct();

        return $this->ticketStateRepository->find($state_id);
    }
}
