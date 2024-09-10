<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Title',
            ])
            ->add('overview', null,[
                'required' => false,
            ])
            ->add('status',choiceType::class,[
                'choices'=>[
                    'Cancelled'=>'Cancelled',
                    'ended'=>'Ended',
                    'returning'=>'Returning',
                ],
                'multiple'=>false,
                ])
            ->add('vote')
            ->add('popularity')
            ->add('genres')
            ->add('firstAirDate', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('lastAirDate', DateType::class, [
                'widget' => 'choice',  // Utilisation de listes déroulantes
                'format' => 'yyyy-dd-MM',  // Ordre : année, jour, mois
                'years' => range(date('Y'), date('Y') - 100),  // Plage d'années personnalisée
                'placeholder' => [
                    'year' => 'Année',
                    'day' => 'Jour',
                    'month' => 'Mois'
                ],
            ])
            ->add('backdrop')
            ->add('poster')
            ->add('tmdbId')
            //->add('dateCreated', null, [
               //'widget' => 'single_text',
            //])
            ->add('dateModified', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
