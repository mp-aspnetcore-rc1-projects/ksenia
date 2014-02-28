<?php
namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Transformer\CollectionToIdCollection;
use Transformer\StringToArray;

class Project extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add("title", "text")
            ->add('language', "choice", array('choices' => array('en' => "English", 'ru' => "Russian")))
            ->add('client', 'text')
            ->add('description', 'textarea')
            ->add($builder->create('tags', 'text', array('required' => false))
                ->addModelTransformer(new StringToArray()));

//        //@note @symfony form events
//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
//            // if model has id ,then add images field;
//            /** @link http://symfony.com/doc/current/cookbook/form/dynamic_form_modification.html */
//            $project = $event->getData();
//            $form = $event->getForm();
//            if ($project && $project->getId() !== null) {
//                $form->add('images', new Images());
//            }
//        });
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "project";
    }
}