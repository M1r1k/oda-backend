<?php

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TicketTypeController extends FOSRestController
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $TicketTypeRepository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    public function manualConstruct()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->ticketTypeRepository = $this->entityManager->getRepository('ODADneprMockServiceBundle:TicketType');
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Returns list of ticket types",
     *   output="ODADnepr\MockServiceBundle\Entity\TicketType",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     *
     * @Route("/rest/v1/ticket-types")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $this->manualConstruct();

        $tickets = $this->ticketTypeRepository->findAll();
        return $tickets;
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Returns Ticket Type with requested ID",
     *   requirements={
     *     {
     *       "name"="type_id",
     *       "dataType"="integer",
     *       "required"=true,
     *       "description"="Type ID"
     *     }
     *   },
     *   output="ODADnepr\MockServiceBundle\Entity\TicketType",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     *
     * @Route("/rest/v1/ticket-category/{type_id}")
     * @Method({"GET"})
     */
    public function getAction($type_id)
    {
        $this->manualConstruct();

        return $this->ticketTypeRepository->find($type_id);
    }
}
