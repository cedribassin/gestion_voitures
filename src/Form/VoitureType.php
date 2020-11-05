<?php

namespace App\Form;

use App\Entity\Modele;
use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('immatriculation')
            ->add('nbPortes')
            ->add('annee')
            //On indique que ça fait référence à l'entité Modele
            ->add('modele', EntityType::class, [
                'class'=> Modele::class,
                //On précise le champs à prendre en compte -> libelle
                'choice_label'=> 'libelle'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
