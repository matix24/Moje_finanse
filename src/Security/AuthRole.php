<?php

namespace App\Security;

/**
 * Klasa definiująca role użytkowników systemu
 *
 * Class AuthRole
 * @package App\Security
 */
class AuthRole
{

    /**
     * domyślna rola zalogowanego użytkownika symfony
     * @var string
     */
    public const ROLE_USER = 'ROLE_USER';

    /**
     * admin zarządzający całym systemem
     * @var string
     */
    public const ROLE_ADMIN = 'ROLE_ADMIN';
} //end class
