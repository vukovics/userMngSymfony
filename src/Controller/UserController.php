<?php

namespace App\Controller;

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

        $em = $this->getDoctrine()->getManager();
        $user= [];
        //IF POST REQUEST
        if($request->getRealMethod() === 'POST'){

            $params = $request->request->all();

            $user = new Users();
            $user->setName($params["name"]);
            $user->setSalary($params["salary"]);
            $user->setEmail($params["email"]);
            $user->setTimeZone($params["timeZone"]);
            $user->setCountry($params["country"]);

            $em->persist($user);

            $em->flush();



            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', [
            'controller_name' => 'UserController',
            'user'=>$user
        ]);

    }

    public function edit($id, Request $request){

        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);

        if($request->getRealMethod() === 'POST'){

            $params = $request->request->all();

            $user->setName($params["name"]);
            $user->setSalary($params["salary"]);
            $user->setEmail($params["email"]);
            $user->setTimeZone($params["timeZone"]);
            $user->setCountry($params["country"]);

            $em->persist($user);

            $em->flush();
        }


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

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController'
        ]);

    }

    public function delete($id){

        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('user_list');

    }

    public function generateCsv()
    {

        return $this->redirectToRoute('user_list');

    }
}
