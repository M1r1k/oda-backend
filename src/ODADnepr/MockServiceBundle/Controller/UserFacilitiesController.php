<?php

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserFacilitiesController extends FOSRestController
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $UserFacilitiesRepository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    public function manualConstruct()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->userFacilitiesRepository = $this->entityManager->getRepository('ODADneprMockServiceBundle:UserFacilities');
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Returns list of user facilities",
     *   output="ODADnepr\MockServiceBundle\Entity\UserFacilities",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     *
     * @Route("/rest/v1/user-facilities")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $this->manualConstruct();

        $tickets = $this->userFacilitiesRepository->findAll();
        return $tickets;
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Returns user facilities with requested ID",
     *   requirements={
     *     {
     *       "name"="facilities_id",
     *       "dataType"="integer",
     *       "required"=true,
     *       "description"="Facilities ID"
     *     }
     *   },
     *   output="ODADnepr\MockServiceBundle\Entity\UserFacilities",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     *
     * @Route("/rest/v1/ticket-category/{facilities_id}")
     * @Method({"GET"})
     */
    public function getAction($facilities_id)
    {
        $this->manualConstruct();

        return $this->userFacilitiesRepository->find($facilities_id);
    }
}
