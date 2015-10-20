<?php

namespace ODADnepr\MockServiceBundle\Service;

use Parse\ParseClient;
use Parse\ParseInstallation;
use Parse\ParsePush;

Class Push {

  const STATE_CHANGES = 1;

  private $parse;
  private $receivers;

  private static $alerts = array(
    self::STATE_CHANGES => 'Статус вашої заявки було змінено.'
  );

  function __construct($receivers = array()) {
    $this->parse = ParseClient::initialize(
      'QjZBEAUz7dexXqX9LHX2oQu1H1cLg6Axltexbcwp',
      '0vg1qwRUSEmxAUdDrqgkooJ44aYog45F0VhM5hsa',
      'A0fNVPyZpsOsJXPqkcRfakGic28LLql3bU4ZCZWf'
    );

    $this->receivers = $receivers;
  }

  public function setReceivers($receivers) {
    $this->receivers = $receivers;
    return $this;
  }

  public function send($type) {
    $query = ParseInstallation::query();

    $query->containedIn("user_id", $this->receivers);
    ParsePush::send(array(
      "where" => $query,
      "data" => array(
        'alert' => self::$alerts[$type]
      )
    ));
  }
}