<?php

namespace Skl\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RechercheType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface  $builder, array $options )
    {
       $builder->add('recherche', 'text', array('label' => false,'attr' => array('class' => 'input-medium search-query')));
        
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'skl_blogbundle_recherchetype';
    }
}
