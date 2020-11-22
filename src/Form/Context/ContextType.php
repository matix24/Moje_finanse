<?php

namespace App\Form\Context;

use App\Entity\Context;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ContextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nazwa kontekstu',
                    'autocomplete'=>'off'
                ],
                'constraints' => [
                    new Length(['min' => 3, 'max' => 32])
                ],
                'label' => 'Nazwa kontekstu'
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Notatka (np. widok konta firmowego)',
                    'autocomplete'=>'off'
                ],
                'constraints' => [
                    new Length(['min' => 1, 'max' => 255])
                ],
                'label' => 'Notatka',
                'required'=>false
            ])
            ->add('icon', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ikona kontekstu',
                    'style'=>"font-family: 'Font Awesome 5 Free OR Solids OR Brands';font-weight: 900;"
                ],
                'choices' => [
                    '&#xf2b9; fa-address-book' => 'fa-address-book',
                    '&#xf26e; fa-address-bookk' => 'fa-address-bookk',
                    '&#xf556; fa-address-bookkk' => 'fa-address-bookkk',
                ],
                'label' => 'Ikona kontekstu',
                'data' => [0],
                'required' => true,
                'multiple' => false,
                'expanded' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => "Zapisz"
            ])
        ;

        //roles field data transformer
        $builder->get('icon')
            ->addModelTransformer(new CallbackTransformer(
                function ($iconsArray) {
                    // transform the array to a string
                    return count($iconsArray) ? $iconsArray[0] : null;
                },
                function ($iconsString) {
                    // transform the string back to string
                    return $iconsString;
                }
            ));
    } // end buildForm

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Context::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task_item'
        ]);
    } // end configureOptions
} // end class
