<?php
namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Transformer\GridFSFileToFile;
use Transformer\StringToArray;

class Image extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add("title", "text")
            ->add('isPublished', 'choice', array('choices' => array('no', 'yes')))
            ->add('description', 'textarea')
            ->add(
                $builder->create('file', 'file', array('required' => false, 'attr' => array('accept' => 'image/*')))
                    ->addModelTransformer(new GridFSFileToFile())
            );
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "image";
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver); // TODO: Change the autogenerated stub
        $resolver->setDefaults(array(
            'data_class' => '\Entity\Image'
        ));
    }


}