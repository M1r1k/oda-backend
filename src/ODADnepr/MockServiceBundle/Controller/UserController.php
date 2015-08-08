<?php

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ODADnepr\MockServiceBundle\Entity\Address;
use ODADnepr\MockServiceBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends FOSRestController
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $userRepository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    protected $serializer;

    public function manualConstruct()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->serializer = $this->get('serializer');
        $this->userRepository = $this->entityManager->getRepository('ODADneprMockServiceBundle:User');
    }

    public function manualResponseHandler($data) {
      $view = $this->view($data);
      return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Creates new user.",
     *   input="ODADnepr\MockServiceBundle\Form\UserType",
     *   output="ODADnepr\MockServiceBundle\Entity\User",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     400="Returned when there is errors"
     *   }
     * )
     * @Route("/rest/v1/user-register")
     * @Method({"POST"})
     */
    public function createUserAction(Request $request)
    {
        $this->manualConstruct();
        $user_object = $this->serializer->deserialize($request->getContent(), 'ODADnepr\MockServiceBundle\Entity\User', 'json');
        $user = $this->saveUserWithRelations($user_object);

        return $user;
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Returns list of users",
     *   output="ODADnepr\MockServiceBundle\Entity\User",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     *
     * @Route("/rest/v1/users")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $this->manualConstruct();

        $users = $this->userRepository->findAll();
        return $this->manualResponseHandler($users);
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Returns user with requested ID",
     *   requirements={
     *     {
     *       "name"="user_id",
     *       "dataType"="integer",
     *       "required"=true,
     *       "description"="User ID"
     *     }
     *   },
     *   output="ODADnepr\MockServiceBundle\Entity\User",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     *
     * @Route("/rest/v1/user/{user_id}")
     * @Method({"GET"})
     */
    public function getAction($user_id)
    {
        $this->manualConstruct();

        $user = $this->userRepository->find($user_id);
        return $this->manualResponseHandler($user);
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Deletes user by given ID",
     *   requirements={
     *     {
     *       "name"="user_id",
     *       "dataType"="integer",
     *       "required"=true,
     *       "description"="User ID"
     *     }
     *   },
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     * @Route("/rest/v1/user/{user_id}")
     * @Method({"DELETE"})
     */
    public function deleteAction($user_id)
    {
        $this->manualConstruct();
        $user = $this->userRepository->find($user_id);
        $this->entityManager->remove($user);
        return ['status message' => 'woohoo!'];
    }

    /**
     * @ApiDoc(
     *   resource=true,
     *   description="AUTHORIZATION REQUIRED!!! Updates user by given ID.",
     *   requirements={
     *     {
     *       "name"="user_id",
     *       "dataType"="integer",
     *       "required"=true,
     *       "description"="User ID"
     *     }
     *   },
     *   input="ODADnepr\MockServiceBundle\Form\UserType",
     *   output="ODADnepr\MockServiceBundle\Entity\User",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized",
     *     404="Given user was not found"
     *   }
     * )
     * @Route("/rest/v1/user/{user_id}")
     * @Method({"PUT"})
     */
    public function putAction(Request $request, $user_id)
    {
        $user_object = json_decode($request->getContent());
        $user = $this->saveUserWithRelations($user_object, true);

      return $this->manualResponseHandler($user);
    }

    protected function saveUserWithRelations(User $user, $update = false) {
        $this->manualConstruct();
        if ($update) {
            $user = $this->userRepository->find($user->getId());
        }
        if (($address = $user->getAddress()) && $address instanceof Address) {

            $this->entityManager->merge($address);
            $user->setAddress($address);
        }
        $validator = $this->get('validator');
        $errors = $validator->validate($user);
        if ($errors->count() == 0) {
            $this->entityManager->merge($user);
            $this->entityManager->flush();
            return $user;
        }
        throw new BadRequestHttpException(json_encode($this->serializer->toArray($errors)));
    }
}
