<?php

namespace App\Controller;

use App\Entity\Roles;
use App\Entity\UserRoles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Users;
use Symfony\Component\Security\Core\User\User;
use Symfony\Flex\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Doctrine\DBAL\Connection;

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
            'users'=>$users
        ]);
    }

    public function create(Request $request)
    {

        $em1 = $this->getDoctrine()->getManager();
        $em2 = $this->getDoctrine()->getManager();

        $role = $this->getDoctrine()->getRepository(Roles::class)->find(1);

        $user= [];
        //IF POST REQUEST
        if($request->getRealMethod() === 'POST'){
            $em1->getConnection()->beginTransaction();
            $em2->getConnection()->beginTransaction();

            $params = $request->request->all();

            // Save user
            $user = new Users();
            $user->setName($params["name"]);
            $user->setSalary($params["salary"]);
            $user->setEmail($params["email"]);
            $user->setTimeZone($params["timeZone"]);
            $user->setCountry($params["country"]);


            // Save user role
            $userRole = new UserRoles();
            $userRole->setRole($role);
            $userRole->setUser($user);

            //Transaction
            try {
                $em1->persist($user);
                $em1->flush();

                $em2->persist($userRole);
                $em2->flush();

                $em1->getConnection()->commit();
                $em2->getConnection()->commit();
            } catch (Exception $e) {
                $em1->getConnection()->rollback();
                $em2->getConnection()->rollback();
                throw $e;
            }

            // Add flash message
            $this->addFlash('success', 'User created!');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', [
            'controller_name' => 'UserController',
            'user'=>$user
        ]);

    }

    public function edit($id, Request $request){

        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);

        return $this->render('user/edit.html.twig', [
            'controller_name' => 'UserController',
            'user'=>$user
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
        //TODO
        //$role = $this->getDoctrine()->getRepository(Roles::class)->findUserId($id);

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
