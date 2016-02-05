<?php

namespace Skl\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('titre')
            ->add('auteur')
            ->add('contenu')
        ;
    }

    public function getName()
    {
        return 'skl_blogbundle_articletype';
    }
}
