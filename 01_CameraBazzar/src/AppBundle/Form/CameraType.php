<?php

namespace AppBundle\Form;

use AppBundle\Entity\Camera;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CameraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('make', TextType::class)
            ->add('model', TextType::class)
            ->add('price', NumberType ::class)
            ->add('quantity', NumberType::class)
            ->add('maxShutterSpeed', NumberType::class)
            ->add('minShutterSpeed', NumberType::class)
            ->add('minIso', NumberType::class)
            ->add('maxIso', NumberType::class)
            ->add('isFullFrame',ChoiceType::class,
                array('choices' => array(
                    'yes' => '1',
                    'no' => '0')))
            ->add('videoResolution', TextType::class)
            ->add('lightMetering', TextType::class)
            ->add('description', TextType::class)
            ->add('imageURL', TextType::class)
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Camera::class
        ));
    }
}
