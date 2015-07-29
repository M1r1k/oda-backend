<?php

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use ODADnepr\MockServiceBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

    /**
     * @Route("/rest/v1/generate/user")
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
     * @Route("/rest/v1/users")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $this->manualConstruct();

        $users = $this->userRepository->findAll();
        return $users;
    }

    /**
     * @Route("/rest/v1/user/{id}")
     * @Method({"GET"})
     */
    public function getAction($id)
    {
        $this->manualConstruct();

        return $this->userRepository->find($id);
    }

    /**
     * @Route("/rest/v1/user/{id}")
     * @Method({"DELETE"})
     */
    public function deleteAction($id)
    {
        $this->manualConstruct();
        $user = $this->userRepository->find($id);
        $this->entityManager->remove($user);
        return ['status message' => 'woohoo!'];
    }

    /**
     * @Route("/rest/v1/user-register")
     * @Method({"POST"})
     */
    public function postAction(Request $request)
    {
        $user_object = json_decode($request->getContent());
        $user = $this->saveUserWithRelations($user_object);
        $jwtProvider = $this->get('lexik_jwt_authentication.jwt_manager');
        return ['user' => $user, 'token' => $jwtProvider->create($user)];
    }

    /**
     * @Route("/rest/v1/user")
     * @Method({"PUT"})
     */
    public function putAction(Request $request)
    {
        $user_object = json_decode($request->getContent());
        $user = $this->saveUserWithRelations($user_object, true);

        return $user;
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
