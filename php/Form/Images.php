<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Transformer\CollectionToIdCollection;

class Images extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->addModelTransformer(new CollectionToIdCollection());
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "images";
    }

    public function getParent()
    {
        return "hidden";
    }


}