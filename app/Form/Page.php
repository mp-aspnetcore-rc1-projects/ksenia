<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Page extends AbstractType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "page";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('title')
            ->add('language', 'choice', array('choices' => array('en' => 'English', 'ru' => 'Russian')))
            ->add('isPublished', 'choice', array('choices' => array('no', 'yes')))
            ->add('category')
            ->add('description', 'textarea', array('attr' => array('rows' => 10,'data-markdown-preview'=>'description')));
    }
}