<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use App\Service\Context\ContextActual;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @todo
 * * TU BĘDĄ STATYSTYKI UŻYTKOWNIKA W DANYM KONTEKŚCIE
 *
 * Class AppController
 * @package App\Controller
 * @Security("is_granted('ROLE_USER')")
 */
class AppController extends AbstractController
{
    /**
     * Główny index aplikacji
     *
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {
        return $this->render('view/app/app/index.html.twig', [
            'breadcrumb' => [
                $this->generateUrl('app_index') => 'Panel finansowy'
            ]
        ]);
    } // end index
}// end class
