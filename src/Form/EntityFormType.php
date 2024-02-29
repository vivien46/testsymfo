<?php

namespace App\Form;

use App\Entity\Entity1;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> 'Nom',
            ])
            ->add('firstName', TextType::class, [
                'label'=> 'Prénom',
            ])
            ->add('birthDate', DateType::class, [
                'label' => 'Date de Naissance'
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'H',
                    'Femme' => 'F',
                ],
                'expanded' => true,
                'multiple' => false,
                // 'form_attr' => 'form-check form-check-inline'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entity1::class,
        ]);
    }
}
