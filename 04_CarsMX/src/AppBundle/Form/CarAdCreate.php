<?php

namespace AppBundle\Form;

use AppBundle\Entity\CarAd;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarAdCreate extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('make', ChoiceType::class, array('choices' => array(
                'Alfa Romeo' => 'Alfa Romeo',
                'Audi' => 'Audi',
                'BMW' => 'BMW',
                'Chevrolet' => 'Chevrolet',
                'Citroen' => 'Citroen',
                'Dacia' => 'Dacia',
                'Fiat' => 'Fiat',
                'Ford' => 'Ford',
                'Great wall' => 'Great wall',
                'Honda' => 'Honda',
                'Hyundai' => 'Hyundai',
                'Infiniti' => 'Infiniti',
                'Isuzu' => 'Isuzu',
                'Jaguar' => 'Jaguar',
                'Jeep' => 'Jeep',
                'Kia' => 'Kia',
                'Lada' => 'Lada',
                'Land Rover' => 'Land Rover',
                'Lexus' => 'Lexus',
                'Mazda' => 'Mazda',
                'Mercedes-Benz' => 'Mercedes-Benz',
                'Mini' => 'Mini',
                'Mitsubishi' => 'Mitsubishi',
                'Nissan' => 'Nissan',
                'Opel' => 'Opel',
                'Peugeot' => 'Peugeot',
                'Porsche' => 'Porsche',
                'Renault' => 'Renault',
                'Seat' => 'Seat',
                'Skoda' => 'Skoda',
                'Ssangyong' => 'Ssangyong',
                'Subaru' => 'Subaru',
                'Suzuki' => 'Suzuki',
                'Toyota' => 'Toyota',
                'Volvo' => 'Volvo',
                'VW' => 'VW'
            )))
            ->add('model', TextType::class)
            ->add('modification', TextType::class)
            ->add('price', NumberType::class)
            ->add('transmission', ChoiceType::class, array('choices' => array(
                'a' => 'a'
            )))
            ->add('fuel', ChoiceType::class, array('choices' => array(
                'a' => 'a'
            )))
            ->add('enginePower', NumberType::class)
            ->add('cubicCapacity', NumberType::class)
            ->add('manufactureYear', NumberType::class)
            ->add('mileage', NumberType::class)
            ->add('doors', ChoiceType::class, array('choices' => array(
                'a' => 'a'
            )))
            ->add('color', ChoiceType::class, array('choices' => array(
                'a' => 'a'
            )))
            ->add('mainImage', TextType::class)
            ->add('description', TextareaType::class)
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CarAd::class
        ));
    }
}
