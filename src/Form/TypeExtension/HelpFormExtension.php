<?php
/**
 * Created by PhpStorm.
 * User: Paradoxs
 * Date: 06.06.2018
 * Time: 16:55
 */

namespace App\Form\TypeExtension;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HelpFormExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['help']) {
            $view->vars['help'] = $options['help'];
        }
    }


    public function getExtendedType()
    {
        return FormType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('help', null);
    }


}