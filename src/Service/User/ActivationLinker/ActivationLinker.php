<?php

namespace App\Service\User\ActivationLinker;

use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ActivationLinker
{

    /**
     * Sól dla szyfru sha256
     * @var string
     */
    private const SALT = 'dfhsefug4k54634k564jk5g64j564j';

    /**
     * @var UrlGeneratorInterface
     */
    protected UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Zwracam link potrzebny do rejestracji i aktywacji konta
     * '/user/account?email=[email]&sec=sha256(email)';
     * @param string $email
     * @return string
     */
    public function generateActivationLink(string $email): string
    {
        return $this->router->generate('app_activateUserAccount', ['email' => $email, 's' => $this->getEmailHash($email)], 0);
    } // end generateActivationLink

    /**
     * Generuje hash
     * @param string $email
     * @return string
     */
    public function getEmailHash(string $email)
    {
        return hash('sha256', static::SALT . $email . static::SALT);
    } // end getEmailHash

}// end class
