<?php

namespace App\Controller\Context;

use App\Entity\Context;
use App\Entity\User;
use App\Entity\Product;
use App\Form\Context\ContextType;
use App\Service\Toast;
use App\Twig\HelperForDataTable\PrettyCheckInDataTable;
use App\Twig\HelperForDataTable\PrettyDateInDataTable;
use App\Twig\HelperForDataTable\PrettyYesNoInDataTable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @todo
 * * BRAKUJE IKON W FORMULARZU
 * * sprawdzić widok mobilny !!
 *
 * Class ContextController
 * @package App\Controller
 * @Security("is_granted('ROLE_USER')")
 */
class ContextController extends AbstractController
{
    /**
     * Główny index aplikacji
     *
     * @Route("/context", name="app_context_index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('view/app/context/context/index.html.twig');
    } // end index

    /**
     * JSON dla DataTable listy kontekstów dla użytkownika
     *
     * @Route("/context/dataTableJson", name="app_context_dataTableJson")
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
        $paginator = $entityManager->getRepository(Context::class)
            ->fetchForPaginator(
                $this->getUser(),
                $startFromElement,
                $request->get('length'),
                $request->get('search')['value'],
                $request->get('filter'),
                $request->get('order'),
                $request->get('columns')
            );

        $total = $paginator->count();
        $resultArray = [];
        $resultArray['draw'] = $request->get('draw');
        $resultArray['recordsTotal'] = $total;
        $resultArray['recordsFiltered'] = $total;
        $resultArray['itemPerPages'] = $request->get('length');
        $resultArray['page'] = $startFromElement;
        $resultArray['data'] = [];
        /** @var Context $context */
        foreach ($paginator as $context) {
            $resultArray['data'][] = [
                $context->getName(),
                $context->getDescription(),
                PrettyYesNoInDataTable::prettyPrint($context->isArchive()),
                PrettyDateInDataTable::prettyPrint($context->getCreatedAt()),
                PrettyDateInDataTable::prettyPrint($context->getUpdatedAt()),
                '<a class="btn btn-xs btn-primary" href="'.$this->generateUrl('app_context_edit', ['id' => $context->getId()]).'" data-toogle="tooltip" title="Edycja kontekstu"><i class="fas fa-edit"></i></a> '
                . '<button class="btn btn-xs ' . ($context->isArchive() ? 'btn-success context-to-enable' : 'btn-danger context-to-disable') . '" data-link="' . $this->generateUrl('app_context_archive', ['id' => $context->getId()]) . '" data-toggle="tooltip" title="' . ($context->isArchive() ? 'Włącz kontekst' : 'Wyłącz kontekst') . '"><i class="fas fa-power-off"></i></button> '
                . '<button class="btn btn-xs btn-danger context-to-delete" data-link="' . $this->generateUrl('app_context_delete', ['id' => $context->getId()]) . '" data-toggle="tooltip" title="Usuń kontekst"><i class="fas fa-trash"></i></button>'
            ];
        } // end foreach

        return $this->json($resultArray, 200);
    } // end dataTableJson

    /**
     * Dodaje nowy kontekst do aplikacji
     *
     * @todo
     * * ikony select
     *
     * @Route("/context/add", name="app_context_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $context = new Context();
        $form = $this->createForm(ContextType::class, $context, [
            'action' => $this->generateUrl('app_context_add'),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contextForm = $form->getData();
            $contextForm->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contextForm);

            try {
                $entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
                $form->get('name')->addError(new FormError('Podana nazwa już istnieje'));
                return $this->render('view/app/context/context/add_edit.html.twig', [
                    'form'=>$form->createView(),
                    'action'=>'Dodaj'
                ]);
            }

            $this->addFlash(Toast::SUCCESS, 'Zapisano!');
            return $this->redirectToRoute('app_context_index');
        }

        return $this->render('view/app/context/context/add_edit.html.twig', [
            'form'=>$form->createView(),
            'action'=>'Dodaj'
        ]);
    } // end add

    /**
     * Edytuje dany kontekst
     *
     * @todo
     * * ikony select
     *
     * @Route("/context/edit/{id}", name="app_context_edit")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function edit(int $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var Context $context */
        $context = $entityManager->getRepository(Context::class)->find($id);
        if ($context === null || !$context->checkPermission($this->getUser())) {
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranego kontekstu');
            return $this->redirectToRoute('app_context_index');
        }

        $form = $this->createForm(ContextType::class, $context, [
            'action' => $this->generateUrl('app_context_edit', ['id'=>$id]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($form->getData());
            try {
                $entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
                $form->get('name')->addError(new FormError('Podana nazwa już istnieje'));
                return $this->render('view/app/context/context/add_edit.html.twig', [
                    'form'=>$form->createView(),
                    'action'=>'Edytuj'
                ]);
            }

            $this->addFlash(Toast::SUCCESS, 'Zaktualizowano!');
            return $this->redirectToRoute('app_context_index');
        }

        return $this->render('view/app/context/context/add_edit.html.twig', [
            'form'=>$form->createView(),
            'action'=>'Edytuj'
        ]);
    } // end edit

    /**
     * @todo
     * * BRAKUJE SPRAWDZENIA CZY UŻYTKOWNIK NIE USUNĄŁ SWÓJ OSTATNI KONTEKST
     * * PO USUNIĘCIU TRZEBA ZAKTUALIZOWAĆ WARTOŚĆ W SESJI
     *
     * Usuwam dany kontekst lub jeżeli był w użyciu to go archiwizuje
     *
     * @Route("/context/delete/{id}", name="app_context_delete")
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var Context $context */
        $context = $entityManager->getRepository(Context::class)->find($id);
        if ($context === null || !$context->checkPermission($this->getUser())) {
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranego kontekstu');
            return $this->redirectToRoute('app_context_index');
        }

        // @TODO DO NAPRAWY
        # muszę sprawdzić czy to nie jest ostatni kontekst danego użytkownika
        $countContext = $entityManager->getRepository(Context::class)->countContextForUser($this->getUser());
        if ($countContext < 2) {
            $this->addFlash(Toast::WARNING, 'Nie można usunąć ostatniego kontekstu użytkownika.');
            return $this->redirectToRoute('app_context_index');
        }

        $entityManager->remove($context);
        try {
            $entityManager->flush();
        } catch (\Throwable $th) {
            $context->setIsArchive(true);
            $entityManager->persist($context);
            $entityManager->flush();

            $this->addFlash(Toast::WARNING, 'Kontekst jest u użyciu i został zarchiwizowany.');
            return $this->redirectToRoute('app_context_index');
        }
        $this->addFlash(Toast::SUCCESS, 'Usunięto.');
        return $this->redirectToRoute('app_context_index');
    } // end delete


    /**
     * @todo
     * * TO SAMO CO W DELETE
     *
     * Archiwizuje dany kontekst
     *
     * @Route("/context/archive/{id}", name="app_context_archive")
     * @param int $id
     * @return Response
     */
    public function archive(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var Context $context */
        $context = $entityManager->getRepository(Context::class)->find($id);
        if ($context === null || !$context->checkPermission($this->getUser())) {
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranego kontekstu');
            return $this->redirectToRoute('app_context_index');
        }

        if ($context->isArchive()) {
            $context->setIsArchive(false);
        } else {
            $context->setIsArchive(true);
        }

        $entityManager->persist($context);
        $entityManager->flush();

        $this->addFlash(Toast::SUCCESS, 'Zaktualizowano.');
        return $this->redirectToRoute('app_context_index');
    } // end archive
}// end class
