<?php

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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

    public function manualConstruct()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
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
    public function generateDevContentAction(Request $request)
    {
        $this->manualConstruct();
        $user_object = json_decode($request->getContent());
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
     *   description="AUTHORIZATION REQUIRED!!! Creates new user.",
     *   input="ODADnepr\MockServiceBundle\Form\UserType",
     *   output="ODADnepr\MockServiceBundle\Entity\User",
     *   statusCodes={
     *     200="Returned when authorization was successful",
     *     403="Returned when the user is not authorized"
     *   }
     * )
     * @Route("/rest/v1/user")
     * @Method({"POST"})
     */
    public function postAction(Request $request)
    {
        $user_object = json_decode($request->getContent());
        $user = $this->saveUserWithRelations($user_object);
        $jwtProvider = $this->get('lexik_jwt_authentication.jwt_manager');
        $data = ['user' => $user, 'token' => $jwtProvider->create($user)];
        return $this->manualResponseHandler($data);
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

    protected function saveUserWithRelations(\stdClass $userObject, $update = false) {
        $this->manualConstruct();
        $odaEntityManager = $this->get('oda.oda_entity_manager');
        $address = $odaEntityManager->setAddress($userObject->address);
        if ($update && ($user = $this->userRepository->find($userObject->id))) {
        }
        else {
            $user = new User();
        }
        $user->setAddress($address);
        $user->setBirthday($userObject->birthday);
        $user->setEmail($userObject->email);
        $user->setFirstName($userObject->first_name);
        $user->setLastName($userObject->last_name);
        $user->setImage($userObject->image);
        $user->setPhone($userObject->phone);
        $user->setPassword($userObject->password);
        $validator = $this->get('validator');
        $errors = $validator->validate($user);
        if ($errors->count() == 0) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $user;
        }
        $serializer = $this->get('serializer');
        throw new BadRequestHttpException(json_encode($serializer->toArray($errors)));
    }
}
