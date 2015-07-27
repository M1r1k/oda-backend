<?php

/*
 * This file is part of the FOSRestBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ODADnepr\MockServiceBundle\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use JMS\Serializer\Serializer;


/**
 * This listener handles ensures that for specific formats AccessDeniedExceptions
 * will return a 403 regardless of how the firewall is configured
 *
 * @author Lukas Kahwe Smith <smith@pooteeweet.org>
 */
class AuthenticationSuccessEventListener
{
    private $serializer;
    /**
     * Constructor.
     *
     * @param array           $formats    An array with keys corresponding to request formats or content types
     *                                    that must be processed by this listener
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

  public function onAuthenticationSuccess(AuthenticationSuccessEvent $event) {
    $data = $event->getData();
    $user = $event->getUser();
    $data['user'] = $this->serializer->toArray($user);
    $event->setData($data);
  }

    public static function getSubscribedEvents()
    {
        return array(
          Events::AUTHENTICATION_SUCCESS => array('onAuthenticationSuccess', 5),
        );
    }
}
