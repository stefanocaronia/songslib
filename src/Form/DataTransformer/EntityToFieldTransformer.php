<?php

namespace App\Form\DataTransformer;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToFieldTransformer implements DataTransformerInterface
{
    private string $class;
    private string $field;

    private ObjectManager $manager;

    public function __construct(ObjectManager $manager, array $options)
    {
        $this->class = @$options['class'];
        $this->field = @$options['field'];
        $this->manager = $manager;
    }

    public function transform($entity): string
    {
        if (null === $entity) {
            return '';
        }

        return $entity->{'get'. ucfirst($this->field)}();
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        $entity = $this->manager->getRepository($this->class)->findOneBy([$this->field => $value]);

        if (null === $entity) {
            throw new TransformationFailedException(sprintf(
                'An entity with ' . $this->field. ' = "%s" does not exist!',
                $value
            ));
        }

        return $entity;
    }
}