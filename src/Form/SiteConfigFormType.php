<?php


namespace Symka\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symka\Core\Entity\SiteConfigEntity;


class SiteConfigFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('domain', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'In develop' => SiteConfigEntity::STATUS_IN_DEVELOP,
                    'Active' => SiteConfigEntity::STATUS_ACTIVE,
                    'Close' => SiteConfigEntity::STATUS_CLOSE
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])

            ->add('templatePath', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank()
                ]
            ])

            ->add('adminTemplatePath', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank()
                ]
            ])

        ;
    }
}