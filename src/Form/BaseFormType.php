<?php

namespace App\Form;

use App\Entity\Song;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BaseFormType extends AbstractType
{
    protected TranslatorInterface $translator;
    protected RouterInterface $router;
    protected EntityManagerInterface $em;

    public function __construct(TranslatorInterface $translator, RouterInterface $router, EntityManagerInterface $em)
    {
        $this->translator = $translator;
        $this->router = $router;
        $this->em = $em;
    }
}