<?php

namespace App\Form;

use App\Entity\Coaster;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoasterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: [
                'label' => 'Nom de la montagne russe'
            ])
            ->add('maxSpeed', options: [
                'label' => 'Vitesse max (km/h)'
            ])
            ->add('length', options: [
                'label' => 'Longueur (m)'
            ])
            ->add('maxHeight', options: [
                'label' => 'Hauteur max (m)'
            ])
            ->add('operating', ChoiceType::class, options: [
                'label' => 'En service ?',
                'choices'  => [ //choix disponibles
                    'Oui' => true,
                    'Non' => false,

                ],
                'expanded' => true, //affiche sous forme de boutons radio
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coaster::class,
        ]);
    }
}
