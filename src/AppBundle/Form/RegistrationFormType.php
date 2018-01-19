<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 07-01-2018
 * Time: 14:13
 */

namespace AppBundle\Form;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->remove('username');
    }

    public function getParent()
    {
        return BaseRegistrationFormType::class;
    }
}
