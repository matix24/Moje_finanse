<?php

namespace App\Service\Context;

use App\Entity\Context;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class ContextActual
{
    const ACTUAL_CONTEXT = 'actualContext';

    /** @var SessionInterface $session */
    private SessionInterface $session;

    /** @var EntityManagerInterface $entityManager */
    private EntityManagerInterface $entityManager;

    /** @var User|null  */
    private ?User $user;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager, Security $security)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->user = $security->getUser();
    }

    /**
     * Zwracam aktualny kontekst użytkownika
     *
     * @return Context
     */
    public function getActualContext(): Context
    {
        $context = $this->session->get('actualContext', null);
        if (is_null($context)) {
            $context = $this->findContext();
            $this->saveContext($context);
        }
        return $context;
    } // end getActualContext

    /**
     * Zwracam listę wszystkich aktualnie dostępnych kontekstów dla danego użytkownika
     *
     * @return Context[]
     */
    public function getContextAll()
    {
        return $this->entityManager->getRepository(Context::class)->findBy(['user'=>$this->user, 'is_archive'=>0]);
    }// end getContextAll

    /**
     * Zmieniam kontekst na wybrany lub biorę pierwszy z bazy
     * i go zwracam
     *
     * @param Context|null $context
     * @return Context
     */
    public function changeContext(Context $context = null): Context
    {
        if (is_null($context)) {
            $context = $this->findContext();
        }
        $this->saveContext($context);
        return $context;
    } // end changeContext

    /**
     * Wyszukuje pierwszy kontekst użytkownika w bazie
     *
     * @return Context|null
     */
    private function findContext(): ?Context
    {
        return $this->entityManager->getRepository(Context::class)->findOneBy(['user'=>$this->user]);
    } // end findContext

    /**
     * Zapisuje wybrany kontekst
     *
     * @param Context $context
     * @return void
     */
    private function saveContext(Context $context): void
    {
        $this->deleteContext();
        $this->session->set(static::ACTUAL_CONTEXT, $context);
    } // end saveContext

    /**
     * Usuwam aktualny kontekst
     *
     * @return void
     */
    private function deleteContext(): void
    {
        $this->session->remove(static::ACTUAL_CONTEXT);
    } // end deleteContext
} // end class
