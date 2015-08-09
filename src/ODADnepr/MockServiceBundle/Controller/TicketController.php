<?php

namespace ODADnepr\MockServiceBundle\Controller;

use Doctrine\ORM\Query;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ODADnepr\MockServiceBundle\Entity\Ticket;
use ODADnepr\MockServiceBundle\Entity\TicketState;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TicketController extends BaseController
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $ticketRepository;

    public function manualConstruct()
    {
        parent::manualConstruct();
        $this->ticketRepository = $this->entityManager->getRepository('ODADneprMockServiceBundle:Ticket');
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

        return $this->manualResponseHandler($this->ticketRepository->find($id));
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
        return $this->manualResponseHandler(['status message' => 'woohoo!']);
    }

    /**
     * @Route("/rest/v1/ticket")
     * @Method({"POST"})
     */
    public function postAction(Request $request)
    {
        $this->manualConstruct();
        $ticket_object = $this->serializer->deserialize($request->getContent(), 'ODADnepr\MockServiceBundle\Entity\Ticket', 'json');
        $ticket = $this->saveTicketWithRelations($ticket_object);

        return $this->manualResponseHandler($ticket);
    }

    /**
     * @Route("/rest/v1/ticket/{ticket_id}")
     * @Method({"PUT"})
     */
    public function putAction(Request $request, $ticket_id)
    {
        $this->manualConstruct();
        $ticket_object = $this->serializer->deserialize($request->getContent(), 'ODADnepr\MockServiceBundle\Entity\Ticket', 'json');
        $ticket = $this->saveTicketWithRelations($ticket_object, $ticket_id);

        return $this->manualResponseHandler($ticket);
    }

    protected function saveTicketWithRelations(Ticket $ticketObject, $ticket_id = null)
    {
        $this->manualConstruct();
        if ($ticket_id) {
            $ticket = $this->ticketRepository->find($ticket_id);
            if (!$ticket) {
                throw new NotFoundHttpException('Ticket was not found');
            }
        } else {
            $ticket = new Ticket();
            $ticket->setCreatedDate(time());
            $ticket->setState($this->entityManager->getRepository('ODADneprMockServiceBundle:TicketState')->find(TicketState::NEW_TICKET));
        }
        $ticket->setAddress($this->odaManager->setAddress($ticketObject->getAddress()));

        $ticket->setUser($this->odaManager->getUser($ticketObject->getUser()));
        $ticket->setCategory($this->odaManager->getCategory($ticketObject->getCategory()));
        $ticket->setType($this->odaManager->getType($ticketObject->getType()));
        $ticket->setTitle($ticketObject->getTitle());
        $ticket->setBody($ticketObject->getBody());
        $ticket->setCompletedDate($ticketObject->getCompletedDate());
        $ticket->setTicketId($ticketObject->getTicketId());
        $ticket->setImage($ticketObject->getImage());
        $ticket->setComment($ticketObject->getComment());

        $validator = $this->get('validator');
        $errors = $validator->validate($ticket);
        if ($errors->count() > 0) {
            throw new BadRequestHttpException(json_encode($this->serializer->toArray($errors)));
        }

        if ($ticket_id) {
            $this->entityManager->merge($ticket);
        }
        else {
            $this->entityManager->persist($ticket);
        }
        $this->entityManager->flush();

        return $ticket;
    }
}
