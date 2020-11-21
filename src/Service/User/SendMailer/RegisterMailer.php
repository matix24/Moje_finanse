<?php

namespace App\Service\User\SendMailer;

use App\Entity\User;
use Twig\Environment;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\Service\User\ActivationLinker\ActivationLinker;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * Klasa odpowiedzialna za wysyłanie emaili z linkami aktywacyjnymi
 */
class RegisterMailer
{

    /**
     * Zmienna przechowująca user do którego zostanie wysłany email rejestracyjny
     * @var User
     */
    private User $user;

    /**
     * Wysyłka emaili
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * Twig template
     * @var Environment
     */
    private Environment $templating;

    /**
     * Generator linków aktywacyjnych
     * @var ActivationLinker
     */
    private ActivationLinker $linker;

    /**
     * Zmienne środowiskowe
     * @var ContainerBagInterface
     */
    private ContainerBagInterface $params;

    public function __construct(MailerInterface $mailer, Environment $templating, ActivationLinker $linker, ContainerBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->linker = $linker;
        $this->params = $params;
    }

    /**
     * Ustawiam użytkownika
     * @param User $user
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    } // end setUser

    /**
     * Wysyłam email aktywacyjny dla konta utworzonego przez administratora
     * @return void
     * !! @todo  poprawić adres email from
     */
    public function sendMail()
    {
        $email = new Email();
        $email->to($this->user->getEmail());
        $email->from('crm@szumiela.com.pl');
        $email->subject('Założenie konta');
        $email->html($this->templating->render('layout/app/email_templates/user/registerActivation.html.twig', [
            'user' => $this->user,
            'link' => $this->linker->generateActivationLink($this->user->getEmail())
        ]));
        $this->mailer->send($email);
    } // end sendMail

}// end class