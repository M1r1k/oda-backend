<?php

namespace ODADnepr\MockServiceBundle\Controller;

use Doctrine\ORM\Query;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use ODADnepr\MockServiceBundle\Entity\Ticket;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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

    protected function manualResponseHandler($data) {
        $view = $this->view($data);
        return $this->handleView($view);
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
     * @ApiDoc(
     *   resource=true,
     *   description="Returns list of tickets",
     *   output="ODADnepr\MockServiceBundle\Entity\Ticket",
     *   filters={
     *     {"name"="state", "dataType"="string"},
     *     {"name"="title", "dataType"="string"},
     *     {"name"="category", "dataType"="string"}
     *   },
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     *
     * @Route("/rest/v1/tickets")
     * @Method({"GET"})
     * @Template
     */
    public function indexAction(Request $request)
    {
        $this->manualConstruct();
        $keys = [
            'category' => 'category',
            'state' => 'state',
            'title' => 'title',
            'amount' => 'amount',
            'offset' => 'offset',
        ];

        $args = array_intersect_key($request->query->all(), $keys);

        if (!empty($args)) {
            $where = $parameters = [];

            if (!empty($args['category'])) {
                $where[] = "t.category=:category";
                $parameters['category'] = $args['category'];
            }
            if (!empty($args['state'])) {
                $where[] = "t.state IN (:state)";
                $parameters['state'] = explode(',', $args['state']);
            }
            if (!empty($args['title'])) {
                $where[] = "t.title LIKE :title";
                $parameters['title'] = '%' . $args['title'] . '%';
            }

            /** @var Query $query */
            $query = $this->entityManager->createQuery(
                'SELECT t
                FROM ODADneprMockServiceBundle:Ticket t' .
                (empty($where) ? '' : (' WHERE (' . implode(') AND (', $where) . ')'))
            );

            $query->setParameters($parameters);


            if (!empty($args['amount'])) {
                $query->setMaxResults($args['amount']);
            }

            if (!empty($args['offset'])) {
                $query->setFirstResult($args['offset']);
            }

            $tickets = $query->getResult();
        }
        else {
            $tickets = $this->ticketRepository->findAll();
        }
        return $this->manualResponseHandler($tickets);
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
        $manager = $odaEntityManager->getManager($ticketObject->manager);
        $category = $odaEntityManager->getCategory($ticketObject->category);
        if ($update && ($ticket = $this->ticketRepository->find($ticketObject->id))) {

        } else {
            $ticket = new Ticket();
            $ticket->setCreatedDate(time());
        }
        $ticket->setUser($user);
        $ticket->setAddress($address);
        $ticket->setManager($manager);
        $ticket->setCategory($category);
        $ticket->setTitle(!empty($ticketObject->title) ? $ticketObject->title : '');
        $ticket->setBody($ticketObject->body);
        if (!empty($ticketObject->completed_date)) {
            $ticket->setCompletedDate($ticketObject->completed_date);
        }
        $ticket->setState($ticketObject->state);
        $ticket->setTicketid(!empty($ticketObject->ticket_id) ? $ticketObject->ticket_id : '');
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
