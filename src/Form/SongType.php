<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Song;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SongType extends BaseFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $yearChoices = [];
        for ($year = 1970; $year <= date('Y'); $year++) {
            $yearChoices[$year] = $year;
        }

        $builder
            ->add('title', TextType::class, [
                'label' => $this->translator->trans('Title'),
                'required' => true,
            ])
            ->add('compositionYear', ChoiceType::class, [
                'label' => $this->translator->trans('Composition year'),
                'choices' => $yearChoices
            ])
//            ->add('artists', CollectionType::class, [
//                'label' => $this->translator->trans('Artists'),
//                'entry_type' => ArtistType::class,
//                'entry_options' => [
//                    'embedded' => true,
//                ],
//                'allow_add' => true,
//                'allow_delete' => true,
//                'prototype' => true,
//            ])
            ->add('artists', CollectionType::class, [
                'label' => $this->translator->trans('Artists'),
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'label' => false,
                    'class' => Artist::class,
                    'attr' => [
                        'class' => 'entity-autocomplete',
                    ],
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ])
            ->add('albums', CollectionType::class, [
                'label' => $this->translator->trans('Albums'),
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'label' => false,
                    'class' => Album::class,
                    'attr' => [
                        'class' => 'entity-autocomplete',
                    ],
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Song::class,
        ]);
    }
}