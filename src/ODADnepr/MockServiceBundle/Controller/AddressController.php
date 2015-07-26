<?php

namespace ODADnepr\MockServiceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use FOS\RestBundle\Controller\FOSRestController;



class AddressController extends FOSRestController
{
    /**
     * @Route("/rest/v1/address/districts")
     * @Method({"GET"})
     */
    public function getDistrictsAction()
    {
      $string = $this->soapAddressGetter(['Request' => 'Districts']);
      $districts = new \SimpleXMLElement($string);
      $districts_map = array();
      foreach ($districts->District as $district) {
        $vars = get_object_vars($district);
        $districts_map[$vars['@attributes']['id']] = $vars['@attributes']['name'];
      }
      return $districts_map;
    }

    /**
     * @Route("/rest/v1/address/cities/{district_id}")
     */
    public function getCitiesAction($district_id) {
      $string = $this->soapAddressGetter(['Request' => 'Cities', 'CitiesForDistrictId' => $district_id]);
      $cities = new \SimpleXMLElement($string);
      $cities_map = array();
      foreach ($cities->Cities->City as $city) {
        $vars = get_object_vars($city);
        $cities_map[$vars['@attributes']['id']] = $vars['@attributes']['name'];
      }
      return $cities_map;
    }

    /**
     * @Route("/rest/v1/address/streets/{city_id}")
     * @Template()
     */
    public function getStreetsAction($city_id) {
      $string = $this->soapAddressGetter([
        'Request' => 'Streets',
        'StreetsForCityId' => $city_id
      ]);
      $xml_items = new \SimpleXMLElement($string);
      $entities = array();
      foreach ($xml_items->Streets->Street as $xml_item) {
        $vars = get_object_vars($xml_item);
        $entities[$vars['@attributes']['id']] = $vars['@attributes']['name'];
      }
      return $entities;
    }

    /**
     * @Route("/rest/v1/address/houses/{street_id}")
     * @Template()
     */
    public function getHousesAction($street_id)
    {
      $string = $this->soapAddressGetter([
        'Request' => 'Houses',
        'HousesForStreetId' => $street_id,
      ]);
      var_dump(htmlspecialchars($string));
      $xml_items = new \SimpleXMLElement($string);
      $entities = array();
      foreach ($xml_items->Houses->House as $xml_item) {
        $vars = get_object_vars($xml_item);
        $entities[$vars['@attributes']['id']] = $vars['@attributes']['nomber'];
      }
      return $entities;
    }

    protected function soapAddressGetter($fields) {
      $ch = curl_init();
      $fields += array(
        'AppVersion' => 2,
        'BranchVersion' => 1,
        'OS' => 'wp',
      );
      $postvars = '';
      foreach($fields as $key=>$value) {
        $postvars .= $key . "=" . $value . "&";
      }
      $url = "http://e-contact.dp.gov.ua/mobile/?PHPSESSID=rfh8p8kls64ra0haqokr6cd910";
      curl_setopt($ch,CURLOPT_URL,$url);
      curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
      curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,5);
      curl_setopt($ch,CURLOPT_TIMEOUT, 20);

      //  curl_setopt($ch, CURLOPT_HEADER, true);

      curl_setopt( $ch, CURLOPT_COOKIESESSION, false );
      curl_setopt( $ch, CURLOPT_COOKIEFILE, "/tmp/phpcurl");
      curl_setopt( $ch, CURLOPT_COOKIEJAR, "/tmp/phpcurl" );

      $response = curl_exec($ch);

      return $response;
    }
}
