<?php

namespace ODADnepr\MockServiceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use ODADnepr\MockServiceBundle\Entity\TicketState;
use ODADnepr\MockServiceBundle\Service\Push;

Class AdminAPIController extends BaseController {

  /**
   * @var \Doctrine\Common\Persistence\ObjectRepository
   */
  protected $ticketRepository;

  public function manualConstruct() {
    parent::manualConstruct();

    $this->ticketRepository = $this->entityManager->getRepository('ODADneprMockServiceBundle:Ticket');
  }

  /**
  * @Route("/sync/tickets")
  * @Method("GET")
  */
  public function ansynckedTicketsAction(Request $request) {
    $this->manualConstruct();

    $limit = $request->get('limit') ? intval($request->get('limit')) : null;
    $offset = $request->get('offset') ? intval($request->get('offset')) : null;

    $tickets = $this->ticketRepository->findBy(array(
      'ticket_id' => ''
    ), array(
      'created_date' => 'DESC'
    ), $limit, $offset);

    return $this->manualResponseHandler($tickets);
  }

  /**
   * @Route("/sync/tickets")
   * @Method("POST")
   */
  public function syncTicketAction(Request $request) {
    $this->manualConstruct();

    $reqData = array();
    foreach (json_decode($request->getContent()) as $k => $v) {
      $reqData[intval($v->id)] = $v;
    }

    $q = $this->entityManager->createQuery('select t from ODADnepr\MockServiceBundle\Entity\Ticket t WHERE t.id IN ('. implode(', ', array_keys($reqData)) .')');

    $this->save($q->iterate(), function($ticket) use ($reqData) {
      $ticket->setTicketId($reqData[$ticket->getId()]->tiket_id);
    });

    return new Response();
  }

  /**
   * @Route("/sync/tickets/update")
   * @Method("POST")
   */
  public function updateTicketAction(Request $request){
    $this->manualConstruct();

    $data = array();
    foreach (json_decode($request->getContent()) as $v) {
      $data[$v->ticket_id] = $v->update;
    }

    $states = array();
    foreach ($this->entityManager->getRepository('ODADneprMockServiceBundle:TicketState')->findAll() as $s) {
      $states[$s->getId()] = $s;
    }

    $condition = implode(', ', array_map(function($item){
      return "'" . $item . "'";
    }, array_keys($data)));

    $q = $this->entityManager->createQuery('select t from ODADnepr\MockServiceBundle\Entity\Ticket t WHERE t.ticket_id IN ('. $condition .')');

    $pushesData = array();
    $this->save($q->iterate(), function($ticket) use ($states, $data){
      $ticket->setStateId($data[$ticket->getTicketId()]->state_id, $states);

      $pushesData[] = $ticket->getUserId();
    });

    $push = new Push($pushesData);
    $push->send(Push::STATE_CHANGES);

    return new Response();
  }

  private function save($iterableResult, $func) {
    $batchSize = 20; $i = 0;
    foreach ($iterableResult as $row) {
      $func($row[0]);

      if (($i % $batchSize) === 0) {
        $this->entityManager->flush(); // Executes all updates.
        $this->entityManager->clear(); // Detaches all objects from Doctrine!
      }
      ++$i;
    }
    $this->entityManager->flush();
  }
}