<?php

namespace ODADnepr\MockServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends Controller
{
    /**
     * @Route("/rest/v1/user-auth")
     * @Template()
     */
    public function loginAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/rest/v1/user-auth-check")
     * @Template()
     */
    public function loginCheckAction()
    {
        return array(
                // ...
            );    }

}
