<?php

namespace App\Form;

use App\Entity\Roles;
use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use DateTimeZone;

class UsersType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        // GET TIMEZONES AND CREATE ASSOCIATIVE ARRAY
        $timezone_identifiers = DateTimeZone::listIdentifiers();

        $europeTimezone = array_combine(array_values($timezone_identifiers), array_values($timezone_identifiers));


        $builder
            ->add('name', null, [
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', null, [
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('salary', null, [
                'required' => true,
                'attr' => ['class' => 'form-control']])
            ->add('country', null, [
                'required' => true,
                'attr' => ['class' => 'form-control']])
            ->add('timeZone', ChoiceType::class, array(
                'required' => true,
                'choices' => $europeTimezone,
                'attr' => ['class' => 'form-control']
            ))
            ->add('roleId', ChoiceType::class, array(
                'required' => true,
                'choices' => array_flip([
                    1 => "Developer",
                    2 => "Tester",
                    3 => "Product Owner",
                    4 => "Scrum Master",
                ]),
                'attr' => ['class' => 'form-control'],
                'label' => 'Role'
            ))
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function fconfigureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
