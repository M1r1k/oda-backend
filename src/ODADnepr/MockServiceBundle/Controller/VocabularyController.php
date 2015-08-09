<?php

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VocabularyController extends BaseController
{

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="Returns list of managers",
     *   output="ODADnepr\MockServiceBundle\Entity\Manager",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     * @Route("/rest/v1/vocabulary/managers")
     * @Method({"GET"})
     */
    public function getAllManagersAction()
    {
        $this->manualConstruct();

        $managers = $this->entityManager
          ->getRepository('ODADneprMockServiceBundle:Manager')
          ->findAll();
        return $this->manualResponseHandler($managers);
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="Returns Manager with requested ID",
     *   requirements={
     *     {
     *       "name"="manager_id",
     *       "dataType"="integer",
     *       "required"=true,
     *       "description"="Manager ID"
     *     }
     *   },
     *   output="ODADnepr\MockServiceBundle\Entity\Manager",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     404="Returned when there is no manager"
     *   }
     * )
     *
     * @Route("/rest/v1/vocabulary/manager/{manager_id}")
     * @Method({"GET"})
     */
    public function getManagerAction($manager_id)
    {
        $this->manualConstruct();

        $manager = $this->entityManager
          ->getRepository('ODADneprMockServiceBundle:Manager')
          ->find($manager_id);

      if (!$manager) {
        throw new NotFoundHttpException('Manager was not found');
      }

        return $this->manualResponseHandler($manager);
    }

  /**
   * @ApiDoc(
   *   resource=true,
   *   description="Returns list of user social condition",
   *   output="ODADnepr\MockServiceBundle\Entity\UserSocialCondition",
   *   statusCodes={
   *     200="Returned when authorization was successful",
   *   }
   * )
   *
   * @Route("/rest/v1/vocabulary/user-social-conditions")
   * @Method({"GET"})
   */
  public function getAllSocialConditionsAction()
  {
    $this->manualConstruct();

    $conditions = $this->entityManager
      ->getRepository('ODADneprMockServiceBundle:SocialCondition')
      ->findAll();
    return $this->manualResponseHandler($conditions);
  }

  /**
   * @ApiDoc(
   *   resource=true,
   *   description="Returns user social condition with requested ID",
   *   requirements={
   *     {
   *       "name"="social_condition_id",
   *       "dataType"="integer",
   *       "required"=true,
   *       "description"="Social Condition ID"
   *     }
   *   },
   *   output="ODADnepr\MockServiceBundle\Entity\UserSocialCondition",
   *   statusCodes={
   *     200="Returned when authorization was successful",
   *     404="Returned when the condition was not found"
   *   }
   * )
   *
   * @Route("/rest/v1/vocabulary/user-social-condition/{social_condition_id}")
   * @Method({"GET"})
   */
  public function getAction($social_condition_id)
  {
    $this->manualConstruct();

    $condition = $this->entityManager
      ->getRepository('ODADneprMockServiceBundle:SocialCondition')
      ->find($social_condition_id);

    if (!$condition) {
      throw new NotFoundHttpException('Social Condition was not found');
    }

    return $this->manualResponseHandler($condition);
  }

  /**
   * @ApiDoc(
   *   resource=true,
   *   description="Returns list of user facilities",
   *   output="ODADnepr\MockServiceBundle\Entity\Facilities",
   *   statusCodes={
   *     200="Returned when authorization was successful",
   *   }
   * )
   *
   * @Route("/rest/v1/vocabulary/user-facilities")
   * @Method({"GET"})
   */
  public function getAllFacilitiesAction()
  {
    $this->manualConstruct();

    $facilities = $this->entityManager
      ->getRepository('ODADneprMockServiceBundle:Facilities')
      ->findAll();
    return $this->manualResponseHandler($facilities);
  }

  /**
   * @ApiDoc(
   *   resource=true,
   *   description="Returns user facilities with requested ID",
   *   requirements={
   *     {
   *       "name"="facilities_id",
   *       "dataType"="integer",
   *       "required"=true,
   *       "description"="Facilities ID"
   *     }
   *   },
   *   output="ODADnepr\MockServiceBundle\Entity\Facilities",
   *   statusCodes={
   *     200="Returned when authorization was successful",
   *     404="Returned when the facilities was not found"
   *   }
   * )
   *
   * @Route("/rest/v1/vocabulary/user-facilities/{facilities_id}")
   * @Method({"GET"})
   */
  public function getFacilitiesAction($facilities_id)
  {
    $this->manualConstruct();

    $facilities = $this->entityManager
      ->getRepository('ODADneprMockServiceBundle:Facilities')
      ->find($facilities_id);

    if (!$facilities) {
      throw new NotFoundHttpException('Facilities was not found');
    }

    return $this->manualResponseHandler($facilities);
  }

  /**
   * @ApiDoc(
   *   resource=true,
   *   description="Returns list of ticket types",
   *   output="ODADnepr\MockServiceBundle\Entity\TicketType",
   *   statusCodes={
   *     200="Returned when authorization was successful",
   *   }
   * )
   *
   * @Route("/rest/v1/vocabulary/ticket-types")
   * @Method({"GET"})
   */
  public function getAllTypesAction()
  {
    $this->manualConstruct();

    $types = $this->entityManager
      ->getRepository('ODADneprMockServiceBundle:TicketType')
      ->findAll();
    return $this->manualResponseHandler($types);
  }

  /**
   * @ApiDoc(
   *   resource=true,
   *   description="Returns Ticket Type with requested ID",
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
   *     404="Returned when the type was not found"
   *   }
   * )
   *
   * @Route("/rest/v1/vocabulary/ticket-type/{type_id}")
   * @Method({"GET"})
   */
  public function getTypeAction($type_id)
  {
    $this->manualConstruct();

    $type = $this->entityManager
      ->getRepository('ODADneprMockServiceBundle:TicketType')
      ->find($type_id);

    if (!$type) {
      throw new NotFoundHttpException('Ticket type was not found');
    }

    return $this->manualResponseHandler($type);
  }

  /**
   * @ApiDoc(
   *   resource=true,
   *   description="Returns list of ticket states",
   *   output="ODADnepr\MockServiceBundle\Entity\TicketState",
   *   statusCodes={
   *     200="Returned when authorization was successful",
   *   }
   * )
   *
   * @Route("/rest/v1/vocabulary/ticket-states")
   * @Method({"GET"})
   */
  public function getStatesAction()
  {
    $this->manualConstruct();

    $states = $this->entityManager
      ->getRepository('ODADneprMockServiceBundle:TicketState')
      ->findAll();
    return $this->manualResponseHandler($states);
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
   *     404="Returned when the state was not found"
   *   }
   * )
   *
   * @Route("/rest/v1/vocabulary/ticket-state/{state_id}")
   * @Method({"GET"})
   */
  public function getStateAction($state_id)
  {
    $this->manualConstruct();

    $state = $this->entityManager
      ->getRepository('ODADneprMockServiceBundle:TicketState')
      ->find($state_id);

    if (!$state) {
      throw new NotFoundHttpException('Ticket state was not found');
    }

    return $this->manualResponseHandler($state);
  }

  /**
   * @ApiDoc(
   *   resource=true,
   *   description="Returns list of ticket categories",
   *   output="ODADnepr\MockServiceBundle\Entity\TicketCategory",
   *   statusCodes={
   *     200="Returned when authorization was successful",
   *   }
   * )
   *
   * @Route("/rest/v1/vocabulary/ticket-categories")
   * @Method({"GET"})
   */
  public function getCategoriesAction()
  {
    $this->manualConstruct();

    $categories = $this->entityManager
      ->getRepository('ODADneprMockServiceBundle:TicketCategory')
      ->findAll();
    return $this->manualResponseHandler($categories);
  }

  /**
   * @ApiDoc(
   *   resource=true,
   *   description="Returns Ticket Category with requested ID",
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
   *     404="Returned when the category was not found"
   *   }
   * )
   *
   * @Route("/rest/v1/vocabulary/ticket-category/{category_id}")
   * @Method({"GET"})
   */
  public function getCategoryAction($category_id)
  {
    $this->manualConstruct();

    $category = $this->entityManager
      ->getRepository('ODADneprMockServiceBundle:TicketCategory')
      ->find($category_id);

    if (!$category) {
      throw new NotFoundHttpException('Ticket category was not found');
    }

    return $this->manualResponseHandler($category);
  }
}
