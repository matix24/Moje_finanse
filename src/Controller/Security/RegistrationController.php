<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Entity\Partner;
use App\Form\User\CreatePasswordType;
use App\Security\AuthRole;
use App\Security\EmailVerifier;
use App\Service\Toast;
use Symfony\Component\Mime\Address;
use App\Form\App\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use App\Service\User\ActivationLinker\ActivationLinker;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

/**
 * @todo
 * * POSPRAWDZAĆ REJESTRACJE
 * * REJESTRUJĄCY SIĘ USER POWINIEN DOMYŚLNIE MIEĆ DODANY KONTEKST
 *
 * Class RegistrationController
 * @package App\Controller\Security
 */
class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * Rejestracja użytkownika
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $authenticator
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @todo
     * * Dodać jquery validate w formularzu
     *
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();

            # dodaje uprawnienia dla zwykłego klienta
            $user->setRoles(array(AuthRole::ROLE_USER));

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address($_SERVER['MAILER_FROM'], 'CRM'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('view/app/security/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            // after register log in to app
            // return $guardHandler->authenticateUserAndHandleSuccess(
            //     $user,
            //     $request,
            //     $authenticator,
            //     'main' // firewall name in security.yaml
            // );

            $this->addFlash(Toast::SUCCESS, 'Konto zostało utworzone. Przejdź do skrzynki email, aby aktywować konto.');
            return $this->redirectToRoute('app_index');
        } // end is submitted

        return $this->render('view/app/security/registerlte.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    } // end register

    /**
     * Weryfikacja konta użytkownika po jego samodzielnej rejestracji
     *
     * @Route("/verify/email", name="app_verify_email")
     * @param Request $request
     * @return Response
     */
    public function verifyUserEmail(Request $request): Response
    {

//        dump($this->getUser()); die;

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {



            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());

        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());
            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash(Toast::SUCCESS, 'Twój email został zweryfikowany.');
        return $this->redirectToRoute('app_login');
    } // end verifyUserEmail

    /**
     * Aktywuje danego użytkownika po rejestracji przez administratora systemu
     *
     * @todo pododawać flassmessages dla nowych widoków; dodać jquery validate do formularza
     *
     * @Route("/verify/account/{email}/{s}", name="app_activateUserAccount")
     * @param string $email
     * @param string $s
     * @param Request $request
     * @param ActivationLinker $linker
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function activateUserAccount($email, $s, Request $request, ActivationLinker $linker, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $emailValidate = $validator->validate($email, new Email())->count();
        if ($emailValidate > 0) {
            $this->addFlash(Toast::ERROR, 'Wystąpił błąd podczas aktywowania konta.');
            return $this->redirectToRoute('app_login');
        }

        if ($s !== $linker->getEmailHash($email)) {
            $this->addFlash(Toast::ERROR, 'Wystąpił błąd podczas aktywowania konta.');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(CreatePasswordType::class, null, [
            'action' => $request->getRequestUri(),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findBy(['email' => $email]);
            if ($user === null) {
                $this->addFlash(Toast::ERROR, 'Nie znaleziono użytkownika.');
                return $this->redirectToRoute('app_login');
            }

            if ($user[0]->isVerified()) {
                $this->addFlash(Toast::ERROR, 'Użytkownik został już aktywowany.');
                return $this->redirectToRoute('app_login');
            }

            $user[0]->setIsVerified(true);
            $user[0]->setPassword(
                $passwordEncoder->encodePassword($user[0], $form->get('new_password')->getData())
            );

            $entityManager->persist($user[0]);
            $entityManager->flush();

            $this->addFlash(Toast::SUCCESS, 'Aktywacja powiodła się.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('view/app/security/activateUserAccount.html.twig', ['form' => $form->createView()]);
    } // end activateUserAccount
}// end class
