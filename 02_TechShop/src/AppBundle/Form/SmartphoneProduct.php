<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SmartphoneProduct extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('make', TextType::class)
            ->add('model', TextType::class)
            ->add('originalPrice', NumberType::class)
            ->add('imageAddress', TextType::class)
            ->add('discount', NumberType::class)
            ->add('ram', NumberType::class)
            ->add('resolution', TextType::class)
            ->add('frontCameraResolution', NumberType::class)
            ->add('backCameraResolution', NumberType::class)
            ->add('screenDiagonalSize', NumberType::class)
            ->add('memory', NumberType::class)
            ->add('processorFrequency', NumberType::class)
            ->add('color', TextType::class)
            ->add('submit', SubmitType::class);
    }
}
