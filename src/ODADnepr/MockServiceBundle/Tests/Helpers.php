<?php

namespace ODADnepr\MockServiceBundle\Tests;

trait Helpers {
	protected function assertJsonResponse($response, $statusCode = 200, $checkValidJson =  true, $contentType = 'application/json')
  {
    $this->assertEquals(
      $statusCode, $response->getStatusCode(),
      $response->getContent()
    );
    $this->assertTrue(
      $response->headers->contains('Content-Type', $contentType),
      $response->headers
    );

    if ($checkValidJson) {
      $decode = json_decode($response->getContent());
      $this->assertTrue(($decode != null && $decode != false),
        'is response valid json: [' . $response->getContent() . ']'
      );
    }
  }
}