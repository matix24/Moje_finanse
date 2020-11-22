<?php

namespace App\Controller\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @todo
 * * PO ZALOGOWANIU USTAWIĆ KONTEKST W SESJI
 *
 * Class SecurityController
 * @package App\Controller\Security
 */
class SecurityController extends AbstractController
{
    /**
     * Logowanie
     *
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
//        if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
//        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('view/app/security/loginlte.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Wylogowanie
     *
     * @Route("/logout", name="app_logout")
     * @return Response
     */
    public function logout(): Response
    {
//        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}// end class
