<?php

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use ODADnepr\MockServiceBundle\Entity\Ticket;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TicketController extends FOSRestController
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $ticketRepository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    public function manualConstruct()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->ticketRepository = $this->entityManager->getRepository('ODADneprMockServiceBundle:Ticket');
    }

    /**
     * @Route("/rest/v1/generate/ticket")
     * @Method({"POST"})
     */
    public function generateDevContentAction(Request $request)
    {
        $this->manualConstruct();
        $ticket_object = json_decode($request->getContent());
        $ticket = $this->saveTicketWithRelations($ticket_object);

        return $ticket;
    }

    /**
     * @Route("/rest/v1/tickets")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $this->manualConstruct();

        $tickets = $this->ticketRepository->findAll();
        return $tickets;
    }

    /**
     * @Route("/rest/v1/ticket/{id}")
     * @Method({"GET"})
     */
    public function getAction($id)
    {
        $this->manualConstruct();

        return $this->ticketRepository->find($id);
    }

    /**
     * @Route("/rest/v1/ticket/{id}")
     * @Method({"DELETE"})
     */
    public function deleteAction($id)
    {
        $this->manualConstruct();
        $ticket = $this->ticketRepository->find($id);
        $this->entityManager->remove($ticket);
        return ['status message' => 'woohoo!'];
    }

    /**
     * @Route("/rest/v1/ticket")
     * @Method({"POST"})
     */
    public function postAction(Request $request)
    {
        $ticket_object = json_decode($request->getContent());
        $ticket = $this->saveTicketWithRelations($ticket_object);

        return $ticket;
    }

    /**
     * @Route("/rest/v1/ticket")
     * @Method({"PUT"})
     */
    public function putAction(Request $request)
    {
        $ticket_object = json_decode($request->getContent());
        $ticket = $this->saveTicketWithRelations($ticket_object, true);

        return $ticket;
    }

    protected function saveTicketWithRelations(\stdClass $ticketObject, $update = false)
    {
        $this->manualConstruct();
        $odaEntityManager = $this->get('oda.oda_entity_manager');
        $address = $odaEntityManager->setAddress($ticketObject->address);
        $user = $odaEntityManager->getUser($ticketObject->user);
        if ($update && ($ticket = $this->ticketRepository->find($ticketObject->id))) {

        } else {
            $ticket = new Ticket();
            $ticket->setCreatedDate(time());
        }
        $ticket->setUser($user);
        $ticket->setAddress($address);
        $ticket->setManager($ticketObject->manager);
        $ticket->setTitle($ticketObject->title);
        $ticket->setBody($ticketObject->body);
        if (!empty($ticketObject->completed_date)) {
            $ticket->setCompletedDate($ticketObject->completed_date);
        }
        $ticket->setState($ticketObject->state);
        $ticket->setTicketid($ticketObject->ticket_id);
        if (!empty($ticketObject->image)) {
          $ticket->setImage($ticketObject->image);
        }
        if (!empty($ticketObject->comment)) {
            $ticket->setComment($ticketObject->comment);
        }
        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        return $ticket;
    }
}
