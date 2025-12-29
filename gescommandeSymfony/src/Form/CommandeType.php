<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Gestionnaire;
use App\Entity\Livreur;
use App\Entity\Zone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_commande')
            ->add('type_commande')
            ->add('etat')
            ->add('montant')
            ->add('client_id', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'id',
            ])
            ->add('gestionnaire_id', EntityType::class, [
                'class' => Gestionnaire::class,
                'choice_label' => 'id',
            ])
            ->add('livreur_id', EntityType::class, [
                'class' => Livreur::class,
                'choice_label' => 'id',
            ])
            ->add('zone_id', EntityType::class, [
                'class' => Zone::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
