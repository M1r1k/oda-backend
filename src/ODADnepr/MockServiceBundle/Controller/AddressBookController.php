<?php

namespace ODADnepr\MockServiceBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AddressBookController extends BaseController
{
    /**
     * @ApiDoc(
     *   resource=true,
     *   description="Get list of districts.",
     *   output="ODADnepr\MockServiceBundle\Entity\District",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *   }
     * )
     * @Route("/rest/v1/address-book/districts")
     * @Method({"GET"})
     */
    public function getDistrictsAction()
    {
      $this->manualConstruct();
      $districts = $this->entityManager
        ->getRepository('ODADneprMockServiceBundle:District')
        ->findAll();

      return $this->manualResponseHandler($districts);
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="Get list of cities.",
     *   output="ODADnepr\MockServiceBundle\Entity\City",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     404="Returned there is not such district",
     *   }
     * )
     * @Route("/rest/v1/address-book/cities/{district_id}")
     * @Method({"GET"})
     */
    public function getCitiesAction($district_id) {
      $this->manualConstruct();
      $cities = $this->entityManager
        ->getRepository('ODADneprMockServiceBundle:City')
        ->findBy(['district' => $district_id]);
      return $this->manualResponseHandler($cities);
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="Get list of streets.",
     *   output="ODADnepr\MockServiceBundle\Entity\Street",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     404="Returned there is not such city",
     *   }
     * )
     * @Route("/rest/v1/address-book/streets/{city_id}")
     * @Method({"GET"})
     */
    public function getStreetsAction($city_id) {
      $this->manualConstruct();
      $streets = $this->entityManager
        ->getRepository('ODADneprMockServiceBundle:Street')
        ->findBy(['city' => $city_id]);
      return $this->manualResponseHandler($streets);
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="Get list of houses.",
     *   output="ODADnepr\MockServiceBundle\Entity\House",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     404="Returned there is not such street",
     *   }
     * )
     * @Route("/rest/v1/address-book/houses/{street_id}")
     * @Method({"GET"})
     */
    public function getHousesAction($street_id)
    {
      $this->manualConstruct();
      $houses = $this->entityManager
        ->getRepository('ODADneprMockServiceBundle:House')
        ->findBy(['street' => $street_id]);
      return $this->manualResponseHandler($houses);
    }

}
