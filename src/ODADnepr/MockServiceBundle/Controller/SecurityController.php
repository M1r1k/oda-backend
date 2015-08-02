<?php

namespace ODADnepr\MockServiceBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends Controller
{

    /**
     * @ApiDoc(
     *   resource=false,
     *   description="Endpoint for user authorization through JWT",
     *   requirements={
     *     {
     *       "name"="username",
     *       "dataType"="string",
     *       "description"="User name or email"
     *     },
     *     {
     *       "name"="password",
     *       "dataType"="string",
     *       "description"="User password"
     *     },
     *   },
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     401="Returned when Bad Credentials were given"
     *   }
     * )
     *
     * @Route("/rest/v1/user-auth-check")
     * @Method({"POST"})
     * @Template()
     */
    public function loginCheckAction()
    {
        return array(
                // ...
            );    }

}
