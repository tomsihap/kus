<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\Team;
use App\Entity\Tournament;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;


class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo')
            ->add('tournament', EntityType::class, [
                'class' => Tournament::class,
                'choice_label' => 'name',
            ]) 
            ->add('team', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
            ]) 
            ->add('profilPic', FileType::class, [
                'mapped' => false,
                'label' => 'Add a picture',
                'required'   => false,
            ]);

        $builder->get('tournament')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getForm()->getData();
                $teams = $data->getTeams();

                $form->getParent()->add('team', EntityType::class, [
                    'class' => Team::class,
                    'choices' => $teams,
                    'choice_label' => 'name',
                ]);
            }
        );    
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
