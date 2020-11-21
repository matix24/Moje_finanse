<?php

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('old_password', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Wprowadź aktualne hasło',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Hasło musi mieć minimum {{ limit }} znaków',
                        'max' => 4096,
                    ])
                ],
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Aktualne hasło'
                ],
                'label'=>'Aktualne hasło'
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Podane wartości są różne.',
                'required' => true,
                'label'=>false,
                'first_options'  => [
                    'label' => 'Nowe hasło',
                    'attr'=>[
                        'placeholder'=>'Nowe hasło',
                        'class' => 'password-field form-control'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Wprowadź nowe hasło',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Hasło musi mieć minimum {{ limit }} znaków',
                            'max' => 4096,
                        ])
                    ],                    
                ],
                'second_options' => [
                    'label' => 'Powtórz nowe hasło',
                    'attr'=>[
                        'placeholder'=>'Powtórz nowe hasło',
                        'class' => 'password-field form-control'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Powtórz nowe hasło',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Hasło musi mieć minimum {{ limit }} znaków',
                            'max' => 4096,
                        ])
                    ],                    
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-primary'
                ],
                'label'=>'Zmień hasło'                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task_item',
        ]);
    }
}
