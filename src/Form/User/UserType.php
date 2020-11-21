<?php

namespace App\Form\User;

use App\Entity\User;
use App\Security\AuthRole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Wprowadź imię użytkownika'
                ],
                'constraints' => [
                    new Length(['min' => 3, 'max' => 90])
                ],
                'label' => 'Imię użytkownika'
            ])
            ->add('surname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Wprowadź nazwisko użytkownika'
                ],
                'constraints' => [
                    new Length(['min' => 3, 'max' => 90])
                ],
                'label' => 'Nazwisko użytkownika'
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Wprowadź e-mail użytkownika'
                ],
                'constraints' => [
                    new Length(['min' => 3, 'max' => 180])
                ],
                'label' => 'E-mail użytkownika'
            ])
            ->add('roles', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Wybierz rolę dla użytkownika'
                ],
                'choices' => [
                    'User' => AuthRole::ROLE_USER,
                    'Admin' => AuthRole::ROLE_ADMIN
                ],
                'label' => 'Uprawnienia użytkownika',
                'data' => [0],
                'required' => true,
                'multiple' => false,
                'expanded' => false
            ])
            ->add('is_verified', HiddenType::class, [
                'data' => '0'
            ])
            ->add('is_disabled', HiddenType::class, [
                'data' => '0'
            ])
            ->add('send_email', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
                'label' => 'Wysłać email do aktywacji?'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => "Zapisz"
            ]);

        //roles field data transformer
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task_item'
        ]);
    }
} // end class
