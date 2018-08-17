<?php
/**
 * Created by PhpStorm.
 * User: Paradoxs
 * Date: 28.02.2018
 * Time: 12:01
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username')
            ->add('_password', PasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
}