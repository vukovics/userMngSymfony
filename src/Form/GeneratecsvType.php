<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GeneratecsvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roleId', ChoiceType::class, array(
                'required' => true,
                'choices' => array_flip([
                    0 => "All",
                    1 => "Developer",
                    2 => "Tester",
                    3 => "Product Owner",
                    4 => "Scrum Master",
                ]),
                'attr' => ['class' => 'form-control'],
                'label' => 'Generate CSV'
            ))
            ->add('select', SubmitType::class,[
                'attr' => ['class' => 'btn btn-primary btnStyle'],
                'label'=> 'Generate'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
