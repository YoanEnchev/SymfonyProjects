<?php

namespace AppBundle\Form;

use AppBundle\Entity\ProductPurchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SinglePurchase extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', NumberType::class)
            ->add('phone',  TextType::class)
            ->add('city', TextType::class)
            ->add('address', TextType::class)
            ->add('creditCardNumber', TextType::class)
            ->add('inputDateValidThrough', TextType::class)
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductPurchase::class
        ]);
    }

}
