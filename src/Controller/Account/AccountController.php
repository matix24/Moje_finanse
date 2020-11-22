<?php

namespace App\Controller\Account;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @todo
 * * TUTAJ BĘDZIE LISTA WSZYSTKICH KONT BANKOWYCH UŻYTKOWNIKA
 * * DODAWANIE BĘDZIE ZA POMOCĄ MODALA
 * * KONTA BĘDĄ WIDOCZNE TYLKO W DANYM KONTEKŚCIE
 * *
 *
 * Class AccountController
 * @package App\Controller\Account
 * @Security("is_granted('ROLE_USER')")
 */
class AccountController extends AbstractController
{
    /**
     * Lista wszystkich kont bankowych użytkownika
     *
     * @Route("/account", name="app_account_index")
     * @return Response
     */
    public function index(): Response
    {
        dump('Processing...');
        die;
    } // end index
}// end class
