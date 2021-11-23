<?php

namespace App\Form;

use App\Form\DataTransformer\EntityToFieldTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class AutocompleteType extends AbstractType
{
    private EntityManagerInterface $em;
    private RouterInterface $router;

    public function __construct(EntityManagerInterface $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new EntityToFieldTransformer($this->em, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $attr = $view->vars['attr'];
        $class = (@$attr['class'] ? $attr['class'] . ' ' : '') . 'entity-autocomplete';
        $attr['class'] = $class;
        $view->vars['attr'] = $attr;

        $view->vars['url'] = $options['route'] ? $this->router->generate($options['route']) : '';
        $view->vars['placeholder'] = $options['placeholder'];
        $view->vars['params'] = $options['params'];
        $view->vars['field'] = $options['field'];
        $view->vars['entity_name'] = '';

        $id = $view->vars['value'];

        if (!empty($id)) {
            $entity = $this->em->getRepository($options['class'])->find($id);
            $entityId = $entity ? (string)$entity->getId() : null;

            $entityTitle = $id;
            if ($entityId === $id) {
                $entityTitle = $entity->__toString();
            }

            $view->vars['entity_name'] = $entityTitle;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults(array(
                'class' => null,
                'route' => null,
                'field' => null,
                'params' => [],
                'placeholder' => null,
                'query' => null,
            ))
            ->setRequired(array('class', 'route', 'field'));
    }

    public function getParent(): string
    {
        return TextType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'autocomplete_field';
    }

}