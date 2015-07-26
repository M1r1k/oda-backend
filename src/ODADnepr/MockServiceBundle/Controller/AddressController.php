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
     * @Template()
     */
    public function getDistrictsAction()
    {
      $string = utf8_decode(utf8_encode($this->soapAddressGetter(['Request' => 'Districts'])));
      $districts = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . $string);
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
      $xml_items = new \SimpleXMLElement($string);
      $entities = array();
      foreach ($xml_items->Houses->House as $xml_item) {
        $vars = get_object_vars($xml_item);
        $entities[$vars['@attributes']['id']] = $vars['@attributes']['nomber'];
      }
      return $entities;
    }

  /**
   * @Route("/rest/v1/ticket-categories")
   */
  public function getTicketCategories() {
    $string = $this->soapAddressGetter([
      'Request' => 'Categories',
    ]);
    $string = '<Response><Categories><Category id="1" Name="Аграрна політика" /><Category id="2" Name="Архіви та статистика" /><Category id="3" Name="Благоустрій та будівництво" /><Category id="4" Name="Виплата заробітної плати" /><Category id="5" Name="Відповідь не отримано" /><Category id="6" Name="Гральний бізнес" /><Category id="7" Name="Депозити" /><Category id="8" Name="Діяльність засобів масової інформації" /><Category id="9" Name="Ритуальні послуги" /><Category id="10" Name="Діяльність службових осіб" /><Category id="11" Name="Екологія та природні ресурси" /><Category id="12" Name="Житлова політика" /><Category id="13" Name="Зайнятість та безробіття" /><Category id="15" Name="Звернення до членів Уряду та працівників КМУ" /><Category id="14" Name="Захист прав споживачів" /><Category id="16" Name="Земельні відносини" /><Category id="18" Name="Злочини проти особи та власності" /><Category id="19" Name="Компенсація втрат заощаджень в Ощадбанку СРСР" /><Category id="20" Name="Комунальне господарство" /><Category id="21" Name="Кредити" /><Category id="22" Name="Культура та культурна спадщина" /><Category id="23" Name="Майнові питання" /><Category id="24" Name="Митна справа" /><Category id="25" Name="Міграція, міждержавні та міжнаціональні відносини" /><Category id="26" Name="Мовна політика" /><Category id="27" Name="Надання інформації" /><Category id="28" Name="Надзвичайні ситуації" /><Category id="29" Name="Обороноздатність" /><Category id="30" Name="Оподаткування" /><Category id="31" Name="Освіта, наука та інтелектуальна власність" /><Category id="32" Name="Охорона здоров\'я" /><Category id="33" Name="Пенсійне забезпечення" /><Category id="34" Name="Питання правосуддя" /><Category id="35" Name="Підприємницька діяльність" /><Category id="36" Name="Побутові справи" /><Category id="37" Name="Подяка" /><Category id="39" Name="Пропозиції" /><Category id="40" Name="Скарги на &quot;гарячу лінію&quot;" /><Category id="41" Name="Соціальний захист населення" /><Category id="42" Name="Технічна помилка" /><Category id="43" Name="Транспортне обслуговування" /><Category id="44" Name="Туризм" /><Category id="45" Name="Умови праці та трудові відносини" /><Category id="46" Name="Фінансові установи" /><Category id="47" Name="Функціонування мереж зв\'язку" /><Category id="125" Name="Інше" /><Category id="101" Name="Промислова політика" /><Category id="102" Name="Аграрна політика і земельні відносини" /><Category id="103" Name="Транспорт і зв\'язок" /><Category id="104" Name="Економічна, цінова, інвестиційна, зовнішньоекономічна, регіональна політика та будівництво, підприємництво" /><Category id="105" Name="Фінансова, податкова, митна політика" /><Category id="106" Name="Соціальний захист" /><Category id="107" Name="Праця і заробітна плата" /><Category id="112" Name="Забезпечення дотримання законності та охорони правопорядку, реалізація прав і свобод громадян" /><Category id="113" Name="Сім\'я, діти, молодь, гендерна рівність, фізична культура і спорт" /><Category id="114" Name="Культура та культурна спадщина, туризм" /><Category id="115" Name="Освіта, наукова, науково-технічна, інноваційна діяльність та інтелектуальна власність" /><Category id="116" Name="Інформаційна політика, діяльність засобів масової інформації" /><Category id="117" Name="Діяльність об\'єднань громадян, релігія та міжконфесійні відносини" /><Category id="118" Name="Діяльність ВР України, Президента України та КМ України" /><Category id="119" Name="Діяльність центральних органів виконавчої влади" /><Category id="120" Name="Діяльність місцевих органів виконавчої влади" /><Category id="121" Name="Діяльність органів місцевого самоврядування" /><Category id="122" Name="Обороноздатність, суверенітет, міждержавні і міжнаціональні відносини" /><Category id="123" Name="Державне будівництво, адміністративно-територіальний устрій" /><Category id="124" Name="Факти корупції" /></Categories></Response>';
    $xml_items = new \SimpleXMLElement($string);
    $entities = array();
    foreach ($xml_items->Categories->Category as $xml_item) {
      $vars = get_object_vars($xml_item);
      $entities[$vars['@attributes']['id']] = $vars['@attributes']['Name'];
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
