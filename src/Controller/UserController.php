<?php

namespace App\Controller;

use App\Entity\UserRoles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Users;
use Symfony\Component\Security\Core\User\User;
use Symfony\Flex\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Doctrine\DBAL\Connection;
use App\Form\UsersType;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    public function show()
    {

        $repository = $this->getDoctrine()->getRepository(Users::class);

        $users = $repository->findAll();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users'=>$users,

        ]);
    }

    public function create(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(UsersType::class, []);

        $user= [];

        //IF POST REQUEST
        if($request->getRealMethod() === 'POST'){

            $user = new Users();

            $form = $this->createForm(UsersType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $userData = $form->getData();

                $em->persist($userData);
                $em->flush();

                $this->addFlash('success', 'User created!');
            }
        }

        return $this->render('user/create.html.twig', [
            'controller_name' => 'UserController',
            'user'=>$user,
            'form'=> $form->createView()
        ]);

    }

    public function edit($id, Request $request){

        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);

        $repository = $this->getDoctrine()->getRepository(Users::class);

        $users = $repository->findAll();

        $form = $this->createForm(UsersType::class, $users[0]);

        return $this->render('user/edit.html.twig', [
            'controller_name' => 'UserController',
            'user'=>$user,
            'form'=> $form->createView()
        ]);

    }


    public function update(Request $request){

        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);

        $params = $request->request->all();

        $user->setName($params["name"]);
        $user->setSalary($params["salary"]);
        $user->setEmail($params["email"]);
        $user->setTimeZone($params["timeZone"]);
        $user->setCountry($params["country"]);

        $em->persist($user);

        $em->flush();

        // Add flash message
        $this->addFlash('success', 'User edit!');

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController'
        ]);

    }

    public function delete($id){

        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        // Add flash message
        $this->addFlash('success', 'User deleted!');

        return $this->redirectToRoute('user_list');

    }

    public function generateCsv()
    {

        return $this->redirectToRoute('user_list');

    }
}
