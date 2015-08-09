<?php

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserSocialConditionController extends FOSRestController
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $UserSocialConditionRepository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    public function manualConstruct()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->userSocialConditionRepository = $this->entityManager->getRepository('ODADneprMockServiceBundle:UserSocialCondition');
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Returns list of user social condition",
     *   output="ODADnepr\MockServiceBundle\Entity\UserSocialCondition",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     *
     * @Route("/rest/v1/user-social-conditions")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $this->manualConstruct();

        $tickets = $this->userSocialConditionRepository->findAll();
        return $tickets;
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Returns user social condition with requested ID",
     *   requirements={
     *     {
     *       "name"="facilities_id",
     *       "dataType"="integer",
     *       "required"=true,
     *       "description"="Facilities ID"
     *     }
     *   },
     *   output="ODADnepr\MockServiceBundle\Entity\UserSocialCondition",
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

        return $this->userSocialConditionRepository->find($facilities_id);
    }
}
