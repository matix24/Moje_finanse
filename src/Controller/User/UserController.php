<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\User\UserType;
use App\Service\Toast;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\User\SendMailer\RegisterMailer;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ObjectHelper\UserRandomPassword;
use App\Twig\HelperForDataTable\PrettyDateInDataTable;
use App\Twig\HelperForDataTable\PrettyCheckInDataTable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Kontroler odpowiedzialny za zarządzanie użytkownikami systemu
 *
 * Class UserController
 * @package App\Controller\App\User
 * @Security("is_granted('ROLE_ADMIN')")
 */
class UserController extends AbstractController
{

    /**
     * Lista użytkowników
     *
     * @Route("/user/index", name="user_index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('view/user/user/index.html.twig');
    } // end index


    /**
     * Pobieram listę użytkowników dla DataTable
     *
     * @Route("/user/dataTableJson", name="user_dataTableJson", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function dataTableJson(Request $request): Response
    {
        # sprawdzenie czy ajax
        if ($request->getMethod() !== 'POST' && !$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('404');
        }

        $startFromElement = ($request->get('start') <= 0 ? 0 : $request->get('start'));

        $entityManager = $this->getDoctrine()->getManager();
        $paginator = $entityManager->getRepository(User::class)
            ->fetchForPaginator($startFromElement, $request->get('length'), $request->get('search')['value'], $request->get('filter'), $request->get('order'), $request->get('columns'));

        $total = $paginator->count();
        $resultArray = [];
        $resultArray['draw'] = $request->get('draw');
        $resultArray['recordsTotal'] = $total;
        $resultArray['recordsFiltered'] = $total;
        $resultArray['itemPerPages'] = $request->get('length');
        $resultArray['page'] = $startFromElement;
        $resultArray['data'] = [];
        foreach ($paginator as $user) {
            $resultArray['data'][] = [
                $user->getId(),
                $user->getEmail(),
                $user->getRoles(),
                PrettyCheckInDataTable::prettyPrint($user->isVerified()),
                PrettyCheckInDataTable::prettyPrint($user->isDisabled()),
                PrettyDateInDataTable::prettyPrint($user->getCreatedAt()),
                PrettyDateInDataTable::prettyPrint($user->getUpdatedAt()),
                '<a class="btn btn-xs btn-primary" href="'.$this->generateUrl('user_edit', ['id' => $user->getId()]).'" data-toogle="tooltip" title="Edycja użytkownika"><i class="fas fa-edit"></i></a> '
                . '<button class="btn btn-xs ' . ($user->isDisabled() ? 'btn-success user-to-enable' : 'btn-danger user-to-disable') . '" data-link="' . $this->generateUrl('user_disableEnableUserAccount', ['id' => $user->getId()]) . '" data-toggle="tooltip" title="' . ($user->isDisabled() ? 'Włącz konto użytkownika' : 'Wyłącz konto użytkownika') . '"><i class="fas fa-power-off"></i></button>'
            ];
        } // end foreach

        return $this->json($resultArray, 200);
    } // end dataTableJson

    /**
     * Włączam lub wyłączam dane konto użytkownika
     *
     * @Route("user/disableAccount/{id}", name="user_disableEnableUserAccount", requirements={"id"="\d+"})
     * @param int $id Id Użytkownika do obsługi
     * @return Response
     */
    public function disableEnableUserAccount(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if ($user === null) {
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranego użytkownika.');
            return $this->redirectToRoute('user_index');
        }

        if ($user->isDisabled()) {
            $user->setIsDisabled(0);
        } else {
            $user->setIsDisabled(1);
        }

        $entityManager->persist($user);
        $entityManager->flush();
        $entityManager->refresh($user);

        $this->addFlash(Toast::SUCCESS, 'Zaktualizowano pozycję.');
        return $this->redirectToRoute('user_index');
    } // end disableUserAccount

    /**
     * Tworzę nowych użytkowników systemu
     *
     * @todo dodać jqueryValidate do formularza
     *
     * @Route("/user/add", name="user_add")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param RegisterMailer $registerMailer
     * @return Response
     */
    public function add(Request $request, UserPasswordEncoderInterface $passwordEncoder, RegisterMailer $registerMailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('user_add'),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $sendEmail = $form['send_email']->getData();

            $user->setPassword(
                $passwordEncoder->encodePassword($user, UserRandomPassword::generatePassword())
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            if ($sendEmail) {
                $registerMailer->setUser($user);
                $registerMailer->sendMail();
            }

            $this->addFlash(Toast::SUCCESS, 'Dodano użytkownika.');
            return $this->redirectToRoute('user_index');
        }

        return $this->render('view/user/user/add.html.twig', ['form' => $form->createView()]);
    } // end add

    /**
     * Edytuje danego użytkownika
     *
     * @Route("/user/edit/{id}", name="user_edit", requirements={"id"="\d+"})
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function edit(int $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (is_null($user)) {
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranego użytkownika.');
            return $this->redirectToRoute('user_index');
        }

        $form = $this->createForm(UserType::class, $user, [
            'action'=>$this->generateUrl('user_edit', ['id'=>$id]),
            'method'=>'POST'
        ]);
        $form->remove('send_email');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();
            $this->addFlash(Toast::SUCCESS, 'Zaktualizowano pozycję.');
            return $this->redirectToRoute('user_index');
        }

        return $this->render('view/user/user/edit.html.twig', ['form'=>$form->createView()]);
    }// end edit
}// end class
