<?php

namespace App\Controller;

use App\Entity\Roles;
use App\Entity\UserRoles;
use App\Form\GeneratecsvType;
use App\Form\RoleType;
use App\Form\UploadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Users;
use Doctrine\DBAL\Connection;
use App\Form\UsersType;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends Controller
{

    /**
     * @var Connection
     */
    private $connection;

    /**
     * UserController constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    /**
     * Show users
     */
    public function index()
    {

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * Show all users
     */
    public function show(Request $request)
    {

        $repository = $this->getDoctrine()->getRepository(Users::class);

        $users = $repository->findAll();

        $roleForm = $this->createForm(RoleType::class, []);

        $generateCsvForm = $this->createForm(GeneratecsvType::class, []);

        $uploadForm = $this->createForm(UploadType::class, []);

        //IF POST REQUEST
        if ($request->getRealMethod() === 'POST') {

            $roleForm->handleRequest($request);

            $roleInfo = $roleForm->getData();

            if ($roleInfo["roleId"] !== 0) {
                //Get users by role_id
                $users = $this->getDoctrine()
                    ->getRepository(Users::class)
                    ->findBy(array('roleId' => $roleInfo["roleId"]));
            }
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
            'form' => $uploadForm->createView(),
            'roles' => $roleForm->createView(),
            'csvForm' => $generateCsvForm->createView()

        ]);
    }

    /**
     * Create new user on POST req.
     * Show Create new user form on GET req.
     *
     */

    public function create(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(UsersType::class, []);

        $user = [];

        //IF POST REQUEST
        if ($request->getRealMethod() === 'POST') {

            $user = new Users();

            $form = $this->createForm(UsersType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $userData = $form->getData();

                $em->persist($userData);
                $em->flush();

                $this->addFlash('success', 'User created!');

                return $this->redirectToRoute('user_list');
            }
        }

        return $this->render('user/create.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'form' => $form->createView()
        ]);

    }

    /**
     * Edit user on POST req.
     * Edit user form on GET req.
     */

    public function edit($id, Request $request)
    {

        //IF POST REQUEST
        if ($request->getRealMethod() === 'POST') {

            $em = $this->getDoctrine()->getManager();

            $user = $this->getDoctrine()->getRepository(Users::class)->find($id);

            $form = $this->createForm(UsersType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $userData = $form->getData();

                $em->persist($userData);
                $em->flush();

                $this->addFlash('success', 'User updated!');

                return $this->redirectToRoute('user_list');
            }
        }

        $repository = $this->getDoctrine()->getRepository(Users::class);

        $user = $repository->find($id);

        $form = $this->createForm(UsersType::class, $user);

        return $this->render('user/edit.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'form' => $form->createView()
        ]);

    }

    /**
     * Show user info
     */

    public function info($id)
    {

        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);

        $role = $this->getDoctrine()->getRepository(Roles::class)->find($user->getRoleId());

        return $this->render('user/info.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'role' => $role
        ]);

    }

    /**
     * Delete user
     */

    public function delete($id)
    {

        $user = $this->getDoctrine()->getRepository(Users::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        // Add flash message
        $this->addFlash('success', 'User deleted!');

        return $this->redirectToRoute('user_list');

    }

}
