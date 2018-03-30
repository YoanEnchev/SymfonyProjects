<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchCar extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('make', ChoiceType::class,
                array('choices' => array(
                    'Any' => 'Any',
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
                    'VW' => 'VW')))
            ->add('model', TextType::class)
            ->add('fuel', ChoiceType::class, array('choices' => array(
                'Any' => 'Any',
                'Gasoline' => 'Gasoline',
                'Diesel' => 'Diesel',
                'Gas' => 'Gas',
                'Electricity' => 'Electricity'
            )))
            ->add('transmission', ChoiceType::class, array('choices' => array(
                'Any' => 'Any',
                'Manual' => 'Manual',
                'Semiautomatic' => 'Semiautomatic',
                'Automatic' => 'Automatic'
            )))
            ->add('doors', ChoiceType::class, array('choices' => array(
                'Any' => 'Any',
                '2/3' => '2/3',
                '4/5' => '4/5')))
            ->add('fromYear', NumberType::class)
            ->add('maxPrice', NumberType::class)
            ->add('sort', ChoiceType::class, array('choices' => array(
                'Expensive - Cheap' => 'expensiveCheap',
                'Cheap - Expensive' => 'cheapExpensive',
                'Year' => 'Year',
                'Power' => 'Power')))
            ->add('toYear', NumberType::class)
            ->add('submit', SubmitType::class);
    }
}
