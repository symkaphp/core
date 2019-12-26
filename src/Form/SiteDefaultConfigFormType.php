<?php


namespace Symka\Core\Form;

use Symfony\Component\Form\AbstractType;


class SiteDefaultConfigFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('save', SubmitType::class)
        ;
    }
}