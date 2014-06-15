<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Transformer\CollectionToIdCollection;

class Upload extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('images','collection',array(
            'allow_add'  => TRUE,
            'prototype'  => TRUE,
            'attr'=>array('multiple'=>true,'accept'=>'image/*')));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "upload";
    }

}