<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Song;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ->add('single', CheckboxType::class, [
                'label' => $this->translator->trans('Single'),
            ])
            ->add('artists', CollectionType::class, [
                'label' => $this->translator->trans('Artists'),
                'entry_type' => AutocompleteType::class,
                'entry_options' => [
                    'label' => false,
                    'class' => Artist::class,
                    'route' => 'artist_ajax_list',
                    'field' => 'name',
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ])
            ->add('albums', CollectionType::class, [
                'label' => $this->translator->trans('Albums'),
                'entry_type' => AutocompleteType::class,
                'entry_options' => [
                    'label' => false,
                    'class' => Album::class,
                    'route' => 'album_ajax_list',
                    'field' => 'title',
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event): void
    {
        $song = $event->getData();
        $form = $event->getForm();
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSubmit(FormEvent $event): void
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (isset($data['single']) && $data['single'] == 1 && $albumName = $data['title']) {
            $data['albums'] = [$albumName];
            $this->addNewEntity(Album::class, 'title', $albumName);
            $event->setData($data);
        } else {
            $albums = $data['albums'] ?? [];
            foreach ($albums as $albumTitle) {
                $this->addNewEntity(Album::class, 'title', $albumTitle);
            }
        }

        $artists = $data['artists'] ?? [];
        foreach ($artists as $artistName) {
            $this->addNewEntity(Artist::class, 'name', $artistName);
        }
    }

    private function addNewEntity(string $entityClass, string $field, string $value): object
    {
        $found = $this->em->getRepository($entityClass)->findOneBy([$field => $value]);
        $newEntity = null;
        if (!$found) {
            $newEntity = new $entityClass();
            $newEntity->{'set' . ucfirst($field)}($value);
            $newEntity->setCreatedAt(new \DateTime());
            $newEntity->setUpdatedAt(new \DateTime());
            $this->em->persist($newEntity);
            $this->em->flush();
        }

        return $newEntity ?? $found;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Song::class,
        ]);
    }
}