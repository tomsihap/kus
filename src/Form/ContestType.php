<?php

namespace App\Form;

use App\Entity\Contest;
use App\Entity\Game;
use App\Entity\Team;
use App\Entity\Player;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ContestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('player', EntityType::class, [
                'class' => Player::class,
                'choice_label' => 'pseudo',
                
                
            ])

            
            ->add('loser', EntityType::class, [
                'class' => Player::class,
                'choice_label' => 'pseudo',
            ])
            ->add('game', EntityType::class, [
                'class' => Game::class,
                'choice_label' => 'name',
            ]);

            $builder->get('player')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $data = $event->getForm()->getData();
                
                    $winnerTeam = $data->getTeam(); 
                    $loser = $winnerTeam->allExceptThis($winnerTeam);

                    $form->getParent()->add('loser', EntityType::class, [
                        'class' => PLayer::class,
                        'choices' => $loser,
                        'choice_label' => 'pseudo',
                    ]);
                    

                }
            );
            }    

        // $formModifier = function (FormInterface $form, Player $loser = null) {
        //     $rival = null === $loser->getTeam() ? [] : $loser->getTeam()->allExceptThis($loser->getTeam());

        //     $form->add('loser', EntityType::class, [
        //         'class' => 'App\Entity\Player',
        //         'placeholder' => '',
        //         'choices' => $rival,
        //     ]);
        // };

    //     $builder->add
    // }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contest::class,
        ]);
    }
}
