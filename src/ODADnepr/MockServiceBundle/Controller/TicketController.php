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
                $where[] = "t.category IN (:category)";
                $parameters['category'] = explode(',', $args['category']);
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
     * @ApiDoc(
     *   resource=true,
     *   description="Returns ticket",
     *   output="ODADnepr\MockServiceBundle\Entity\Ticket",
     *   requirements={
     *     {
     *       "name"="ticket_id",
     *       "dataType"="integer",
     *       "required"=true,
     *       "description"="Ticket ID"
     *     }
     *   },
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     404="Returned when ticket was not found",
     *   }
     * )
     * @Route("/rest/v1/ticket/{ticket_id}")
     * @Method({"GET"})
     */
    public function getAction($ticket_id)
    {
        $this->manualConstruct();
        $ticket = $this->ticketRepository->find($ticket_id);
        if (!$ticket) {
            throw new NotFoundHttpException('Ticket was not found');
        }

        return $this->manualResponseHandler($ticket);
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="Deletes ticket",
     *   output="ODADnepr\MockServiceBundle\Entity\Ticket",
     *   requirements={
     *     {
     *       "name"="ticket_id",
     *       "dataType"="integer",
     *       "required"=true,
     *       "description"="Ticket ID"
     *     }
     *   },
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     404="Returned when ticket was not found",
     *   }
     * )
     * @Route("/rest/v1/ticket/{ticket_id}")
     * @Method({"DELETE"})
     */
    public function deleteAction($ticket_id)
    {
        $this->manualConstruct();
        $ticket = $this->ticketRepository->find($ticket_id);
        if (!$ticket) {
            throw new NotFoundHttpException('Ticket was not found');
        }
        $this->entityManager->remove($ticket);
        return $this->manualResponseHandler(['status message' => 'woohoo!']);
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Creates ticket.",
     *   input="ODADnepr\MockServiceBundle\Form\TicketType",
     *   output="ODADnepr\MockServiceBundle\Entity\Ticket",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
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
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Updates ticket by given ID.",
     *   requirements={
     *     {
     *       "name"="$ticket_id",
     *       "dataType"="integer",
     *       "required"=true,
     *       "description"="Ticket ID"
     *     }
     *   },
     *   input="ODADnepr\MockServiceBundle\Form\TicketType",
     *   output="ODADnepr\MockServiceBundle\Entity\Ticket",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized",
     *     404="Given ticket was not found"
     *   }
     * )
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


    /**
     * @Route("/rest/v1/user/{ticket_id}/file")
     * @Method({"POST"})
     */
    public function uploadFile(Request $request, $ticket_id) {
        $this->manualConstruct();

        $file = $request->files->get('ticket_image');
        $ticket = $this->ticketRepository->find($ticket_id);
        $ticket->setImageFile($file);
        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

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
