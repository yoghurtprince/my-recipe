<?php

namespace Acme\DemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('item', 'file', array("label"=>"Food Items"));
        $builder->add('recipe', 'file');
        $builder->add('Submit', 'submit');
    }

    public function getName()
    {
        return 'recipe';
    }
}
