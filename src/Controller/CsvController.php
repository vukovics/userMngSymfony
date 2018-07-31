<?php

namespace App\Controller;

use App\Entity\UserRoles;
use App\Form\GeneratecsvType;
use App\Form\UploadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Doctrine\DBAL\Connection;
use League\Csv\Reader;


/**
 * Class CsvController
 * @package App\Controller
 */
class CsvController extends Controller
{

    /**
     * @var Connection
     */
    private $connection;

    /**
     * CsvController constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    /**
     * @return StreamedResponse
     * Generate CSV
     */
    public function generateCsv(Request $request)
    {

        $generateCsvForm = $this->createForm(GeneratecsvType::class, []);

        $generateCsvForm->handleRequest($request);

        $roleInfo = $generateCsvForm->getData();

        $roleId = $roleInfo["roleId"];

        if ($roleId != 0) {
            $roleId = 'WHERE role_id = ' . $roleId;
        } else {
            $roleId = '';
        }

        $response = new StreamedResponse();
        $response->setCallback(function () use ($roleId) {

            $handle = fopen('php://output', 'w+');

            // Header of the CSV file
            fputcsv($handle, array('Name', 'Email', 'Salary', 'Time Zone', 'Country', 'Role'), ',');
            // Query data from database
            $results = $this->connection->query("SELECT name, email, salary, time_zone, country, role_id  FROM Users $roleId");
            // Add the data queried from database
            while ($row = $results->fetch()) {
                fputcsv(
                    $handle, // The file pointer
                    array($row['name'], $row['email'], $row['salary'], $row['time_zone'], $row['country'], $row['role_id']),
                    ',' // Delimiter
                );
            }

            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="users.csv"');

        return $response;
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * Import CSV
     */
    public function importCsv(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $repository = $this->getDoctrine()->getRepository(Users::class);
        $users = $repository->findAll();

        $uploadForm = $this->createForm(UploadType::class, []);

        $uploadForm->handleRequest($request);

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {

            $formData = $uploadForm["upload"]->getData();

            $reader = Reader::createFromPath($formData);
            $results = $reader->fetchAssoc();

            foreach ($results as $row) {
                try {
                    // Create new user
                    $user = (new Users())
                        ->setName($row['Name'])
                        ->setEmail($row['Email'])
                        ->setSalary($row['Salary'])
                        ->setTimeZone($row['Time Zone'])
                        ->setCountry($row['Country'])
                        ->setRoleId($row['Role']);

                    $em->persist($user);

                } catch (\Exception $ex) {
                    $this->addFlash('danger', 'Wrong or corrupted file!');

                    return $this->render('user/index.html.twig', [
                        'controller_name' => 'UserController',
                        'users' => $users,
                        'form' => $uploadForm->createView()

                    ]);
                }
            }

            $em->flush();
            // Add flash message
            $this->addFlash('success', 'Users Added!');

            return $this->redirectToRoute('user_list');

        }

    }

}
