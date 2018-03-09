<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CartPurchase extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone',  TextType::class)
            ->add('city', TextType::class)
            ->add('address', TextType::class)
            ->add('creditCardNumber', TextType::class)
            ->add('inputDateValidThrough', TextType::class)
            ->add('submit', SubmitType::class);
    }
}
