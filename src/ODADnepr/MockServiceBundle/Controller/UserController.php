<?php

namespace ODADnepr\MockServiceBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ODADnepr\MockServiceBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends BaseController
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $userRepository;


    public function manualConstruct()
    {
        parent::manualConstruct();
        $this->userRepository = $this->entityManager->getRepository('ODADneprMockServiceBundle:User');
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
        /* @var User $user_object */
        $user_object = $this->serializer->deserialize($request->getContent(), 'ODADnepr\MockServiceBundle\Entity\User', 'json');
        $user = $this->saveUserWithRelations($user_object);
        $token = $this->get('lexik_jwt_authentication.jwt_manager')->create($user);
        return $this->manualResponseHandler(['user' => $user, 'token' => $token]);
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
        return $this->manualResponseHandler(['status message' => 'woohoo!']);
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
        $this->manualConstruct();
        $user_object = $this->serializer->deserialize($request->getContent(), 'ODADnepr\MockServiceBundle\Entity\User', 'json');
        $user = $this->saveUserWithRelations($user_object, $user_id);

        return $this->manualResponseHandler($user);
    }

    protected function saveUserWithRelations(User $userObject, $user_id = null) {
        $this->manualConstruct();
        if ($user_id) {
            $user = $this->userRepository->find($user_id);
            if (!$user) {
                throw new NotFoundHttpException('User was not found');
            }
        }
        else {
            $user = new User();
            $user->setPassword($userObject->getPassword());
        }
        $user->setFirstName($userObject->getFirstName());
        $user->setLastName($userObject->getLastName());
        $user->setMiddleName($userObject->getMiddleName());
        $user->setBirthday($userObject->getBirthday());
        $user->setEmail($userObject->getEmail());
        $user->setGender($userObject->getGender());
        $user->setImage($userObject->getImage());
        $user->setPhone($userObject->getPhone());
        $user->setAddress($this->odaManager->setAddress($userObject->getAddress()));
        $user->setFacilities($this->odaManager->setFacilities($userObject->getFacilities()));
        $user->setSocialCondition($this->odaManager->setSocialCondition($userObject->getSocialCondition()));
        $validator = $this->get('validator');
        $errors = $validator->validate($user);
        if ($errors->count() > 0) {
            throw new BadRequestHttpException(json_encode($this->serializer->toArray($errors)));
        }
        if ($user_id) {
            $this->entityManager->merge($user);
        }
        else {
            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();
        return $user;
    }
}
